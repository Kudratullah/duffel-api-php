<?php

declare(strict_types=1);

namespace Duffel\Api\Stays;

use Duffel\Api\AbstractApi;

class Quotes extends AbstractApi {
  /**
   * Create a quote for a stay rate.
   *
   * @param string $rateId
   * @return mixed
   */
  public function create(string $rateId): mixed {
    return $this->post('/stays/quotes', ['rate_id' => $rateId]);
  }

  /**
   * Get stay quote by ID.
   *
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/stays/quotes/' . self::encodePath($id));
  }
}
