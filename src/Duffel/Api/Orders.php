<?php

declare(strict_types=1);

namespace Duffel\Api;

class Orders extends AbstractApi {
  /**
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function all(array $parameters = []): mixed {
    return $this->get('/air/orders', $parameters);
  }

  /**
   * @param string $id
   * @return mixed
   */
  public function show(string $id): mixed {
    return $this->get('/air/orders/' . self::encodePath($id));
  }

  /**
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function create(array $params): mixed {
    $filteredParams = \array_filter($params, static fn($value) => null !== $value && (!\is_string($value) || '' !== $value));
    return $this->post('/air/orders', $filteredParams);
  }

  /**
   * Update metadata on an order.
   *
   * @param string $id
   * @param array<string, mixed> $metadata
   * @return mixed
   */
  public function update(string $id, array $metadata): mixed {
    return $this->patch('/air/orders/' . self::encodePath($id), ['metadata' => $metadata]);
  }

  /**
   * Add a service (extra baggage, seat, etc.) to an existing order.
   *
   * @param string $id
   * @param array $services
   * @param array $payment
   * @return mixed
   */
  public function addServices(string $id, array $services, array $payment): mixed {
    return $this->post('/air/orders/' . self::encodePath($id) . '/services', [
      'add_services' => $services,
      'payment' => $payment,
    ]);
  }

  /**
   * Price an order with an intended payment method.
   *
   * @param string $id
   * @param array $payment
   * @return mixed
   */
  public function price(string $id, array $payment): mixed {
    return $this->post('/air/orders/' . self::encodePath($id) . '/price', ['payment' => $payment]);
  }

  /**
   * List available services for an order.
   *
   * @param string $id
   * @return mixed
   */
  public function availableServices(string $id): mixed {
    return $this->get('/air/orders/' . self::encodePath($id) . '/available_services');
  }
}
