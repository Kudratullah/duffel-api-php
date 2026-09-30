<?php

declare(strict_types=1);

namespace Duffel\Api\Identity;

use Duffel\Api\AbstractApi;
use SensitiveParameter;

class CustomerUsers extends AbstractApi {
  public function all(array $parameters = []): mixed {
    return $this->get('/identity/users', $parameters);
  }

  public function show(string $id): mixed {
    return $this->get('/identity/users/' . self::encodePath($id));
  }

  public function create(#[SensitiveParameter] array $params): mixed {
    return $this->post('/identity/users', $params);
  }

  public function update(string $id, #[SensitiveParameter] array $params): mixed {
    return $this->patch('/identity/users/' . self::encodePath($id), $params);
  }
}
