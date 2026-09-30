<?php

declare(strict_types=1);

namespace Duffel\Api\Payments;

use Duffel\Api\AbstractApi;
use SensitiveParameter;

class Cards extends AbstractApi {
  /**
   * Create a card.
   *
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function create(#[SensitiveParameter] array $params): mixed {
    return $this->post('/payments/cards', $params);
  }

  /**
   * Delete a card.
   *
   * @param string $id
   * @return mixed
   */
  public function deleteCard(string $id): mixed {
    return $this->delete('/payments/cards/' . self::encodePath($id));
  }
}
