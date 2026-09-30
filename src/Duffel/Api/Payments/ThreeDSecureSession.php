<?php

declare(strict_types=1);

namespace Duffel\Api\Payments;

use Duffel\Api\AbstractApi;
use SensitiveParameter;

class ThreeDSecureSession extends AbstractApi {
  /**
   * Create a 3D Secure session.
   *
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function create(#[SensitiveParameter] array $params): mixed {
    return $this->post('/payments/three_d_secure_sessions', $params);
  }
}
