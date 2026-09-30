<?php

declare(strict_types=1);

namespace Duffel\Api\Cars;

use Duffel\Api\AbstractApi;

class Searches extends AbstractApi {
  public function create(array $params): mixed {
    return $this->post('/cars/searches', $params);
  }
}
