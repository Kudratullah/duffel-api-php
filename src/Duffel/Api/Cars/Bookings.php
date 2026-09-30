<?php

declare(strict_types=1);

namespace Duffel\Api\Cars;

use Duffel\Api\AbstractApi;

class Bookings extends AbstractApi {
  public function show(string $id): mixed {
    return $this->get('/cars/bookings/' . self::encodePath($id));
  }

  public function create(array $params): mixed {
    return $this->post('/cars/bookings', $params);
  }

  public function cancel(string $id): mixed {
    return $this->post('/cars/bookings/' . self::encodePath($id) . '/actions/cancel');
  }
}
