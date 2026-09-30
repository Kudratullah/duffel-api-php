<?php

declare(strict_types=1);

namespace Duffel\Api;

use Duffel\Client;
use Duffel\HttpClient\JsonArray;
use Duffel\HttpClient\ResponseParser;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractApi {
  public function __construct(protected Client $client) {}

  protected function createOptionsResolver(): OptionsResolver {
    return new OptionsResolver();
  }

  protected function getAsResponse(string $uri, array $params = [], array $headers = []): ResponseInterface {
    return $this->client->getHttpClient()->get(self::prepareUri($uri, $params), $headers);
  }

  protected function get(string $uri, array $params = [], array $headers = []): mixed {
    $response = $this->getAsResponse($uri, $params, $headers);
    return ResponseParser::getContent($response);
  }

  protected function post(string $uri, array $params = [], array $headers = []): mixed {
    $body = self::prepareJsonBody($params);
    if (null !== $body) {
      $headers = self::addJsonContentType($headers);
    }

    $response = $this->client->getHttpClient()->post(self::prepareUri($uri), $headers, $body ?? '');
    return ResponseParser::getContent($response);
  }

  protected function patch(string $uri, array $params = [], array $headers = []): mixed {
    $body = self::prepareJsonBody($params);
    if (null !== $body) {
      $headers = self::addJsonContentType($headers);
    }

    $response = $this->client->getHttpClient()->patch(self::prepareUri($uri), $headers, $body ?? '');
    return ResponseParser::getContent($response);
  }

  protected function put(string $uri, array $params = [], array $headers = []): mixed {
    $body = self::prepareJsonBody($params);
    if (null !== $body) {
      $headers = self::addJsonContentType($headers);
    }

    $response = $this->client->getHttpClient()->put(self::prepareUri($uri), $headers, $body ?? '');
    return ResponseParser::getContent($response);
  }

  protected function delete(string $uri, array $params = [], array $headers = []): mixed {
    $body = self::prepareJsonBody($params);
    if (null !== $body) {
      $headers = self::addJsonContentType($headers);
    }

    $response = $this->client->getHttpClient()->delete(self::prepareUri($uri), $headers, $body ?? '');
    return ResponseParser::getContent($response);
  }

  /**
   * Lazily iterate through cursor-paginated endpoints using PHP Generator.
   *
   * @param string $uri
   * @param array<string, mixed> $params
   * @return \Generator<int, mixed>
   */
  public function iterate(string $uri, array $params = []): \Generator {
    $after = null;

    do {
      $query = $params;
      if (null !== $after) {
        $query['after'] = $after;
      }

      $response = $this->getAsResponse($uri, $query);
      $payload = ResponseParser::getResponsePayload($response);

      if (\is_array($payload)) {
        $data = $payload['data'] ?? [];
        if (\is_array($data)) {
          foreach ($data as $item) {
            yield $item;
          }
        }
        $after = $payload['meta']['after'] ?? null;
      } else {
        $after = null;
      }
    } while (null !== $after && '' !== $after);
  }

  protected static function encodePath(string $uri): string {
    return \rawurlencode($uri);
  }

  protected static function prepareUri(string $uri, array $query = []): string {
    $query = \array_filter($query, static fn($value) => null !== $value);

    if (empty($query)) {
      return $uri;
    }

    $queryString = \http_build_query($query, '', '&', \PHP_QUERY_RFC3986);
    return \str_contains($uri, '?') ? $uri . '&' . $queryString : $uri . '?' . $queryString;
  }

  private static function prepareJsonBody(array $params): ?string {
    $params = \array_filter($params, static fn($value) => null !== $value);

    if (0 === \count($params)) {
      return null;
    }

    if (!\array_key_exists('data', $params)) {
      $params = ['data' => $params];
    }

    return JsonArray::encode($params);
  }

  private static function addJsonContentType(array $headers): array {
    return \array_merge([ResponseParser::CONTENT_TYPE_HEADER => ResponseParser::JSON_CONTENT_TYPE], $headers);
  }
}
