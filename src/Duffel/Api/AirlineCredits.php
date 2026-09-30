<?php

declare(strict_types=1);

namespace Duffel\Api;

class AirlineCredits extends AbstractApi {
  /**
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function all(array $parameters = []): mixed {
    return $this->get('/air/airline_credits', $parameters);
  }

  /**
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/air/airline_credits/' . self::encodePath($id));
  }

  /**
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function create(array $params): mixed {
    return $this->post('/air/airline_credits', $params);
  }
}
