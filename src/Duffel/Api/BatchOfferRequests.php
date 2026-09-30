<?php

declare(strict_types=1);

namespace Duffel\Api;

class BatchOfferRequests extends AbstractApi {
  /**
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/air/batch_offer_requests/' . self::encodePath($id));
  }

  /**
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function create(array $params): mixed {
    return $this->post('/air/batch_offer_requests', $params);
  }
}
