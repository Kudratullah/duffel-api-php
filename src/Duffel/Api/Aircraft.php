<?php

declare(strict_types=1);

namespace Duffel\Api;

class Aircraft extends AbstractApi {
  /**
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function all(array $parameters = []): mixed {
    return $this->get('/air/aircraft', $parameters);
  }

  /**
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/air/aircraft/' . self::encodePath($id));
  }
}
