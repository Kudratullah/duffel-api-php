<?php

declare(strict_types=1);

namespace Duffel\Api;

use SensitiveParameter;

class OrderChanges extends AbstractApi {
  /**
   * @param string $orderChangeOfferId
   * @return mixed
   */
  public function create(string $orderChangeOfferId): mixed {
    $params = [
      "selected_order_change_offer" => $orderChangeOfferId,
    ];

    return $this->post('/air/order_changes', $params);
  }

  /**
   * @param string $id
   * @param array<string, mixed> $payment
   * @return mixed
   */
  public function confirm(string $id, #[SensitiveParameter] array $payment): mixed {
    $resolver = $this->createOptionsResolver();
    $resolver->setRequired(['amount', 'currency', 'type']);

    $resolvedPayment = $resolver->resolve($payment);

    $params = [
      "payment" => $resolvedPayment,
    ];

    return $this->post('/air/order_changes/' . self::encodePath($id) . '/actions/confirm', $params);
  }

  /**
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/air/order_changes/' . self::encodePath($id));
  }
}
