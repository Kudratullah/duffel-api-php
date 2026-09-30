<?php

declare(strict_types=1);

namespace Duffel\Api\Stays;

use Duffel\Api\AbstractApi;

class Accommodation extends AbstractApi {
  /**
   * Search for accommodation suggestions.
   *
   * @param string $query
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function suggestions(string $query, array $parameters = []): mixed {
    $params = \array_merge(['query' => $query], $parameters);
    return $this->get('/stays/accommodation/suggestions', $params);
  }

  /**
   * List accommodations.
   *
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function all(array $parameters = []): mixed {
    return $this->get('/stays/accommodation', $parameters);
  }

  /**
   * Get single accommodation by ID.
   *
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/stays/accommodation/' . self::encodePath($id));
  }
}
