<?php

declare(strict_types=1);

namespace Duffel\Api\Identity;

use Duffel\Api\AbstractApi;

class CustomerUserGroups extends AbstractApi {
  public function all(array $parameters = []): mixed {
    return $this->get('/identity/user_groups', $parameters);
  }

  public function show(string $id): mixed {
    return $this->get('/identity/user_groups/' . self::encodePath($id));
  }

  public function create(array $params): mixed {
    return $this->post('/identity/user_groups', $params);
  }

  public function update(string $id, array $params): mixed {
    return $this->patch('/identity/user_groups/' . self::encodePath($id), $params);
  }

  public function deleteUserGroup(string $id): mixed {
    return $this->delete('/identity/user_groups/' . self::encodePath($id));
  }
}
