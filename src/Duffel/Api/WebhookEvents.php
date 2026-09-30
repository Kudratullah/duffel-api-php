<?php

declare(strict_types=1);

namespace Duffel\Api;

class WebhookEvents extends AbstractApi {
  public function all(array $parameters = []): mixed {
    return $this->get('/webhook_events', $parameters);
  }

  public function show(string $id): mixed {
    return $this->get('/webhook_events/' . self::encodePath($id));
  }

  public function redeliver(string $id): mixed {
    return $this->post('/webhook_events/' . self::encodePath($id) . '/actions/redeliver');
  }
}
