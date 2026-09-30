<?php

declare(strict_types=1);

namespace Duffel\Api\Stays;

use Duffel\Api\AbstractApi;

class Searches extends AbstractApi {
  /**
   * Search for accommodation stays.
   *
   * @param array<string, mixed> $params
   * @return mixed
   */
  public function create(array $params): mixed {
    return $this->post('/stays/searches', $params);
  }

  /**
   * Fetch rates for a search result.
   *
   * @param string $searchResultId
   * @return mixed
   */
  public function fetchAllRates(string $searchResultId): mixed {
    return $this->get('/stays/search_results/' . self::encodePath($searchResultId) . '/rates');
  }
}
