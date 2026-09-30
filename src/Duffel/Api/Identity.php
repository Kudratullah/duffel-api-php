<?php

declare(strict_types=1);

namespace Duffel\Api;

use Duffel\Client;
use Duffel\Api\Identity\ComponentClientKeys;
use Duffel\Api\Identity\CustomerUserGroups;
use Duffel\Api\Identity\CustomerUsers;

class Identity {
  private ?CustomerUsers $users = null;
  private ?CustomerUserGroups $userGroups = null;
  private ?ComponentClientKeys $componentClientKeys = null;

  public function __construct(private Client $client) {}

  public function users(): CustomerUsers {
    return $this->users ??= new CustomerUsers($this->client);
  }

  public function userGroups(): CustomerUserGroups {
    return $this->userGroups ??= new CustomerUserGroups($this->client);
  }

  public function componentClientKeys(): ComponentClientKeys {
    return $this->componentClientKeys ??= new ComponentClientKeys($this->client);
  }
}
