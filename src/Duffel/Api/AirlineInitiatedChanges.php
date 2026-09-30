<?php

declare(strict_types=1);

namespace Duffel\Api;

class AirlineInitiatedChanges extends AbstractApi {
  /**
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function all(array $parameters = []): mixed {
    return $this->get('/air/airline_initiated_changes', $parameters);
  }

  /**
   * @param string $id
   * @param string $action 'accept'
   * @return mixed
   */
  public function accept(string $id): mixed {
    return $this->post('/air/airline_initiated_changes/' . self::encodePath($id) . '/actions/accept');
  }

  /**
   * @param string $id
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function update(string $id, array $params): mixed {
    return $this->patch('/air/airline_initiated_changes/' . self::encodePath($id), $params);
  }
}
