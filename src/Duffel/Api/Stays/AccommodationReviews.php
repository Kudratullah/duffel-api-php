<?php

declare(strict_types=1);

namespace Duffel\Api\Stays;

use Duffel\Api\AbstractApi;

class AccommodationReviews extends AbstractApi {
  /**
   * Get accommodation reviews.
   *
   * @param string $accommodationId
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function show(string $accommodationId, array $parameters = []): mixed {
    return $this->get('/stays/accommodation/' . self::encodePath($accommodationId) . '/reviews', $parameters);
  }
}
