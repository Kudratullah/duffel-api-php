<?php

declare(strict_types=1);

namespace Duffel\Api\Stays;

use Duffel\Api\AbstractApi;

class LoyaltyProgrammes extends AbstractApi {
  /**
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function all(array $parameters = []): mixed {
    return $this->get('/stays/loyalty_programmes', $parameters);
  }
}
