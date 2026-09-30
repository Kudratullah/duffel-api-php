<?php

declare(strict_types=1);

namespace Duffel\HttpClient;

use Duffel\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;

final class ResponseParser {
  public const CONTENT_TYPE_HEADER = 'Content-Type';
  public const JSON_CONTENT_TYPE = 'application/json';

  /**
   * Parse HTTP response body. Returns 'data' payload if available, or decoded array, or raw string.
   */
  public static function getContent(ResponseInterface $response): mixed {
    $body = (string) $response->getBody();

    if (!\in_array($body, ['', 'null', 'true', 'false'], true) && \str_contains($response->getHeaderLine(self::CONTENT_TYPE_HEADER), self::JSON_CONTENT_TYPE)) {
      $decoded = JsonArray::decode($body);

      if (\is_array($decoded) && \array_key_exists('data', $decoded)) {
        return $decoded['data'];
      }

      return $decoded;
    }

    return $body;
  }

  /**
   * Parse full response payload including 'data' and 'meta' pagination if present.
   */
  public static function getResponsePayload(ResponseInterface $response): mixed {
    $body = (string) $response->getBody();

    if (!\in_array($body, ['', 'null', 'true', 'false'], true) && \str_contains($response->getHeaderLine(self::CONTENT_TYPE_HEADER), self::JSON_CONTENT_TYPE)) {
      return JsonArray::decode($body);
    }

    return $body;
  }

  public static function getErrorMessage(ResponseInterface $response): ?string {
    try {
      $payload = self::getResponsePayload($response);
    } catch (RuntimeException) {
      return null;
    }

    if (!\is_array($payload) || !isset($payload['errors']) || !\is_array($payload['errors'])) {
      return null;
    }

    $errors = $payload['errors'];
    $requestId = self::getHeader($response, 'x-request-id');
    $formattedError = self::formatDuffelErrors($errors);

    if (null !== $requestId && '' !== $requestId) {
      return \sprintf('[%s]: %s', $requestId, $formattedError);
    }

    return $formattedError;
  }

  private static function getHeader(ResponseInterface $response, string $name): ?string {
    $headers = $response->getHeader($name);
    return !empty($headers) ? $headers[0] : null;
  }

  private static function formatDuffelErrors(array $errors): string {
    $formatted = [];

    foreach ($errors as $error) {
      if (\is_array($error)) {
        $title = $error['title'] ?? $error['type'] ?? 'Error';
        $message = $error['message'] ?? '';
        $code = isset($error['code']) ? \sprintf(' (%s)', $error['code']) : '';
        $field = isset($error['field']) ? \sprintf(' at field "%s"', $error['field']) : '';

        $formatted[] = \trim(\sprintf('%s%s%s: %s', $title, $code, $field, $message));
      } else if (\is_string($error)) {
        $formatted[] = $error;
      }
    }

    return \implode('; ', $formatted);
  }
}
