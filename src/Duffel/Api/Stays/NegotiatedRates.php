<?php

declare(strict_types=1);

namespace Duffel\Api\Stays;

use Duffel\Api\AbstractApi;

class NegotiatedRates extends AbstractApi {
  /**
   * List negotiated rates.
   *
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function all(array $parameters = []): mixed {
    return $this->get('/stays/negotiated_rates', $parameters);
  }

  /**
   * Get single negotiated rate.
   *
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/stays/negotiated_rates/' . self::encodePath($id));
  }

  /**
   * Create a negotiated rate.
   *
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function create(array $params): mixed {
    return $this->post('/stays/negotiated_rates', $params);
  }

  /**
   * Update negotiated rate.
   *
   * @param string $id
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function update(string $id, array $params): mixed {
    return $this->patch('/stays/negotiated_rates/' . self::encodePath($id), $params);
  }

  /**
   * Delete negotiated rate.
   *
   * @param string $id
   * @return mixed
   */
  public function deleteRate(string $id): mixed {
    return $this->delete('/stays/negotiated_rates/' . self::encodePath($id));
  }
}
