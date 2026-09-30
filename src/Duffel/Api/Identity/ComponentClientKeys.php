<?php

declare(strict_types=1);

namespace Duffel\Api\Identity;

use Duffel\Api\AbstractApi;
use SensitiveParameter;

class ComponentClientKeys extends AbstractApi {
  public function create(#[SensitiveParameter] array $params = []): mixed {
    return $this->post('/identity/component_client_keys', $params);
  }
}
