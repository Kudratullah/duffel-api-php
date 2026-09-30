<?php

declare(strict_types=1);

namespace Duffel\Api;

use SensitiveParameter;

class PaymentIntents extends AbstractApi {
  /**
   * @param array<string, mixed> $payment
   * @return mixed
   */
  public function create(#[SensitiveParameter] array $payment): mixed {
    $params = [
      'amount' => $payment['amount'] ?? null,
      'currency' => $payment['currency'] ?? null,
    ];

    $filteredParams = \array_filter($params, static fn($value) => null !== $value && (!\is_string($value) || '' !== $value));

    return $this->post('/payments/payment_intents', $filteredParams);
  }

  /**
   * @param string $id
   * @return mixed
   */
  public function confirm(string $id): mixed {
    return $this->post('/payments/payment_intents/' . self::encodePath($id) . '/actions/confirm');
  }

  /**
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/payments/payment_intents/' . self::encodePath($id));
  }
}
