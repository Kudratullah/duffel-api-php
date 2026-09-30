<?php

declare(strict_types=1);

namespace Duffel\Api;

class Places extends AbstractApi {
  /**
   * Search for place suggestions (airports, cities).
   *
   * @param string $query
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function suggestions(string $query, array $parameters = []): mixed {
    $params = \array_merge(['query' => $query], $parameters);
    return $this->get('/places/suggestions', $params);
  }
}
