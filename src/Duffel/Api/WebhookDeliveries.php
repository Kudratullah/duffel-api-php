<?php

declare(strict_types=1);

namespace Duffel\Api;

class WebhookDeliveries extends AbstractApi {
  public function all(array $parameters = []): mixed {
    return $this->get('/webhook_deliveries', $parameters);
  }

  public function show(string $id): mixed {
    return $this->get('/webhook_deliveries/' . self::encodePath($id));
  }
}
