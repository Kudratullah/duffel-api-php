<?php

declare(strict_types=1);

namespace Duffel\Api;

class PartialOfferRequests extends AbstractApi {
  /**
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/air/partial_offer_requests/' . self::encodePath($id));
  }

  /**
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function create(array $params): mixed {
    return $this->post('/air/partial_offer_requests', $params);
  }

  /**
   * Get full offer fares for selected partial offers.
   *
   * @param string $id
   * @param array $selectedPartialOffers
   * @return mixed
   */
  public function fares(string $id, array $selectedPartialOffers): mixed {
    return $this->get('/air/partial_offer_requests/' . self::encodePath($id) . '/fares', [
      'selected_partial_offers' => $selectedPartialOffers,
    ]);
  }
}
