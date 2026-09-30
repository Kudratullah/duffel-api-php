<?php

declare(strict_types=1);

namespace Duffel\HttpClient;

use Duffel\Exception\RuntimeException;

final class JsonArray {
  public static function decode(string $json): array {
    try {
      $data = \json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) {
      throw new RuntimeException(\sprintf('json_decode error: %s', $e->getMessage()), $e->getCode(), $e);
    }

    if (!\is_array($data)) {
      throw new RuntimeException(\sprintf('json_decode error: Expected JSON array/object, %s given.', \get_debug_type($data)));
    }

    return $data;
  }

  public static function encode(array $value): string {
    try {
      return \json_encode($value, \JSON_THROW_ON_ERROR | \JSON_UNESCAPED_SLASHES);
    } catch (\JsonException $e) {
      throw new RuntimeException(\sprintf('json_encode error: %s', $e->getMessage()), $e->getCode(), $e);
    }
  }
}
