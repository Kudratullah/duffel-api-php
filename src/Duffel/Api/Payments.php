<?php

declare(strict_types=1);

namespace Duffel\Api;

use SensitiveParameter;

class Payments extends AbstractApi {
  /**
   * List payments.
   *
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function all(array $parameters = []): mixed {
    return $this->get('/air/payments', $parameters);
  }

  /**
   * Get single payment.
   *
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/air/payments/' . self::encodePath($id));
  }

  /**
   * Create a payment.
   *
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
