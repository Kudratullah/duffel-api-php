<?php

declare(strict_types=1);

namespace Duffel\Api;

use SensitiveParameter;

class Offers extends AbstractApi {
  /**
   * @param string $offerRequestId
   * @param array<string, mixed> $parameters
   * @return mixed
   */
  public function all(string $offerRequestId, array $parameters = []): mixed {
    $params = \array_merge(['offer_request_id' => $offerRequestId], $parameters);
    return $this->get('/air/offers', $params);
  }

  /**
   * @param string $id
   * @param bool $returnAvailableServices
   * @return mixed
   */
  public function show(string $id, bool $returnAvailableServices = false): mixed {
    $params = $returnAvailableServices ? ['return_available_services' => 'true'] : [];
    return $this->get('/air/offers/' . self::encodePath($id), $params);
  }

  /**
   * Update passenger details or loyalty accounts on an offer.
   *
   * @param string $offer_id
   * @param string $offer_passenger_id
   * @param string $family_name
   * @param string $given_name
   * @param array<string, mixed> $loyalty_programme_accounts
   * @return mixed
   */
  public function update(
    string $offer_id,
    string $offer_passenger_id,
    #[SensitiveParameter] string $family_name,
    #[SensitiveParameter] string $given_name,
    #[SensitiveParameter] array $loyalty_programme_accounts
  ): mixed {
    $params = [
      'family_name' => $family_name,
      'given_name' => $given_name,
      'loyalty_programme_accounts' => $loyalty_programme_accounts,
    ];

    $filteredParams = \array_filter($params, static fn($value) => '' !== $value && [] !== $value);

    return $this->patch('/air/offers/' . self::encodePath($offer_id) . '/passengers/' . self::encodePath($offer_passenger_id), $filteredParams);
  }

  /**
   * Price an offer with intended payment methods.
   *
   * @param string $offerId
   * @param array<string, mixed> $payment
   * @return mixed
   */
  public function price(string $offerId, #[SensitiveParameter] array $payment): mixed {
    return $this->post('/air/offers/' . self::encodePath($offerId) . '/price', ['payment' => $payment]);
  }

  /**
   * Get upsell offers for a specific offer.
   *
   * @param string $offerId
   * @return mixed
   */
  public function upsellOffers(string $offerId): mixed {
    return $this->get('/air/offers/' . self::encodePath($offerId) . '/upsell_offers');
  }
}
