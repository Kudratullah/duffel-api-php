<?php

declare(strict_types=1);

namespace Duffel\Api\Stays;

use Duffel\Api\AbstractApi;
use SensitiveParameter;

class Bookings extends AbstractApi {
  /**
   * List stay bookings.
   *
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function all(array $parameters = []): mixed {
    return $this->get('/stays/bookings', $parameters);
  }

  /**
   * Get stay booking by ID.
   *
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/stays/bookings/' . self::encodePath($id));
  }

  /**
   * Create a stay booking.
   *
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function create(#[SensitiveParameter] array $params): mixed {
    return $this->post('/stays/bookings', $params);
  }

  /**
   * Update stay booking.
   *
   * @param string $id
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function update(string $id, #[SensitiveParameter] array $params): mixed {
    return $this->patch('/stays/bookings/' . self::encodePath($id), $params);
  }

  /**
   * Cancel stay booking.
   *
   * @param string $id
   * @return mixed
   */
  public function cancel(string $id): mixed {
    return $this->post('/stays/bookings/' . self::encodePath($id) . '/actions/cancel');
  }
}
