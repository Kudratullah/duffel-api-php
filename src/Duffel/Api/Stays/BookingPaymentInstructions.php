<?php

declare(strict_types=1);

namespace Duffel\Api\Stays;

use Duffel\Api\AbstractApi;
use SensitiveParameter;

class BookingPaymentInstructions extends AbstractApi {
  /**
   * Create a booking payment instruction.
   *
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function create(#[SensitiveParameter] array $params): mixed {
    return $this->post('/stays/booking_payment_instructions', $params);
  }
}
