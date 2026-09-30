<?php

declare(strict_types=1);

namespace Duffel\Api;

use SensitiveParameter;

class Payments extends AbstractApi {
  /**
   * @param string $orderId
   * @param array<string, mixed> $payment
   * @return mixed
   */
  public function create(string $orderId, #[SensitiveParameter] array $payment): mixed {
    $resolver = $this->createOptionsResolver();
    $resolver->setRequired(['amount', 'currency', 'type']);

    $resolvedPayment = $resolver->resolve($payment);

    $params = [
      'order_id' => $orderId,
      'payment' => $resolvedPayment,
    ];

    return $this->post('/air/payments', $params);
  }
}
