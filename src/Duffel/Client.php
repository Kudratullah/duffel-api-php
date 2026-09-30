<?php

declare(strict_types=1);

namespace Duffel;

use Duffel\Api\Aircraft;
use Duffel\Api\AirlineCredits;
use Duffel\Api\AirlineInitiatedChanges;
use Duffel\Api\Airlines;
use Duffel\Api\Airports;
use Duffel\Api\BatchOfferRequests;
use Duffel\Api\Cars;
use Duffel\Api\Cities;
use Duffel\Api\Identity;
use Duffel\Api\LoyaltyProgrammes;
use Duffel\Api\OfferRequests;
use Duffel\Api\Offers;
use Duffel\Api\OrderCancellations;
use Duffel\Api\OrderChangeOffers;
use Duffel\Api\OrderChangeRequests;
use Duffel\Api\OrderChanges;
use Duffel\Api\Orders;
use Duffel\Api\PartialOfferRequests;
use Duffel\Api\PaymentIntents;
use Duffel\Api\Payments;
use Duffel\Api\Places;
use Duffel\Api\Refunds;
use Duffel\Api\SeatMaps;
use Duffel\Api\Stays;
use Duffel\Api\WebhookDeliveries;
use Duffel\Api\WebhookEvents;
use Duffel\Api\Webhooks;
use Duffel\Exception\InvalidAccessTokenException;
use Duffel\HttpClient\Builder;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Message\Authentication\Bearer;
use SensitiveParameter;

class Client {
  private const DEFAULT_API_URL = 'https://api.duffel.com/';
  private const DEFAULT_API_VERSION = 'v2';
  private const VERSION = '2.0.0';

  private string $accessToken = '';
  private string $apiUrl = self::DEFAULT_API_URL;
  private string $apiVersion = self::DEFAULT_API_VERSION;
  private Builder $httpClientBuilder;

  // Cached API instances
  private ?Aircraft $aircraft = null;
  private ?Airlines $airlines = null;
  private ?Airports $airports = null;
  private ?OfferRequests $offerRequests = null;
  private ?Offers $offers = null;
  private ?OrderCancellations $orderCancellations = null;
  private ?OrderChangeOffers $orderChangeOffers = null;
  private ?OrderChangeRequests $orderChangeRequests = null;
  private ?OrderChanges $orderChanges = null;
  private ?Orders $orders = null;
  private ?PaymentIntents $paymentIntents = null;
  private ?Payments $payments = null;
  private ?Refunds $refunds = null;
  private ?SeatMaps $seatMaps = null;
  private ?Webhooks $webhooks = null;
  private ?WebhookDeliveries $webhookDeliveries = null;
  private ?WebhookEvents $webhookEvents = null;
  private ?AirlineCredits $airlineCredits = null;
  private ?AirlineInitiatedChanges $airlineInitiatedChanges = null;
  private ?PartialOfferRequests $partialOfferRequests = null;
  private ?BatchOfferRequests $batchOfferRequests = null;
  private ?Cities $cities = null;
  private ?Places $places = null;
  private ?LoyaltyProgrammes $loyaltyProgrammes = null;
  private ?Stays $stays = null;
  private ?Cars $cars = null;
  private ?Identity $identity = null;

  public function __construct(?Builder $httpClientBuilder = null) {
    $this->httpClientBuilder = $builder = $httpClientBuilder ?? new Builder();
    $builder->addPlugin(new HeaderDefaultsPlugin($this->getDefaultHeaders()));
    $this->setUrl($this->apiUrl);
  }

  /**
   * Redact access token from debug dumps (var_dump, print_r, loggers).
   */
  public function __debugInfo(): array {
    return [
      'apiUrl' => $this->apiUrl,
      'apiVersion' => $this->apiVersion,
      'accessToken' => '' !== $this->accessToken ? '[REDACTED]' : '',
    ];
  }

  public function aircraft(): Aircraft {
    return $this->aircraft ??= new Aircraft($this);
  }

  public function airlines(): Airlines {
    return $this->airlines ??= new Airlines($this);
  }

  public function airports(): Airports {
    return $this->airports ??= new Airports($this);
  }

  public function offerRequests(): OfferRequests {
    return $this->offerRequests ??= new OfferRequests($this);
  }

  public function offers(): Offers {
    return $this->offers ??= new Offers($this);
  }

  public function orderCancellations(): OrderCancellations {
    return $this->orderCancellations ??= new OrderCancellations($this);
  }

  public function orderChangeOffers(): OrderChangeOffers {
    return $this->orderChangeOffers ??= new OrderChangeOffers($this);
  }

  public function orderChangeRequests(): OrderChangeRequests {
    return $this->orderChangeRequests ??= new OrderChangeRequests($this);
  }

  public function orderChanges(): OrderChanges {
    return $this->orderChanges ??= new OrderChanges($this);
  }

  public function orders(): Orders {
    return $this->orders ??= new Orders($this);
  }

  public function paymentIntents(): PaymentIntents {
    return $this->paymentIntents ??= new PaymentIntents($this);
  }

  public function payments(): Payments {
    return $this->payments ??= new Payments($this);
  }

  public function refunds(): Refunds {
    return $this->refunds ??= new Refunds($this);
  }

  public function seatMaps(): SeatMaps {
    return $this->seatMaps ??= new SeatMaps($this);
  }

  public function webhooks(): Webhooks {
    return $this->webhooks ??= new Webhooks($this);
  }

  public function webhookDeliveries(): WebhookDeliveries {
    return $this->webhookDeliveries ??= new WebhookDeliveries($this);
  }

  public function webhookEvents(): WebhookEvents {
    return $this->webhookEvents ??= new WebhookEvents($this);
  }

  public function airlineCredits(): AirlineCredits {
    return $this->airlineCredits ??= new AirlineCredits($this);
  }

  public function airlineInitiatedChanges(): AirlineInitiatedChanges {
    return $this->airlineInitiatedChanges ??= new AirlineInitiatedChanges($this);
  }

  public function partialOfferRequests(): PartialOfferRequests {
    return $this->partialOfferRequests ??= new PartialOfferRequests($this);
  }

  public function batchOfferRequests(): BatchOfferRequests {
    return $this->batchOfferRequests ??= new BatchOfferRequests($this);
  }

  public function cities(): Cities {
    return $this->cities ??= new Cities($this);
  }

  public function places(): Places {
    return $this->places ??= new Places($this);
  }

  public function loyaltyProgrammes(): LoyaltyProgrammes {
    return $this->loyaltyProgrammes ??= new LoyaltyProgrammes($this);
  }

  public function stays(): Stays {
    return $this->stays ??= new Stays($this);
  }

  public function cars(): Cars {
    return $this->cars ??= new Cars($this);
  }

  public function identity(): Identity {
    return $this->identity ??= new Identity($this);
  }

  public function getAccessToken(): ?string {
    return $this->accessToken;
  }

  public function setAccessToken(#[SensitiveParameter] string $token): void {
    if ('' === \trim($token)) {
      throw new InvalidAccessTokenException("You need to set a token");
    }

    $this->accessToken = \trim($token);
    $this->httpClientBuilder->addPlugin(new AuthenticationPlugin(new Bearer($this->accessToken)));
  }

  public function getApiVersion(): string {
    return $this->apiVersion;
  }

  public function setApiVersion(string $apiVersion): void {
    $this->apiVersion = $apiVersion;
  }

  public function setUrl(string $url): void {
    $uri = $this->getHttpClientBuilder()->getUriFactory()->createUri($url);
    $this->getHttpClientBuilder()->removePlugin(AddHostPlugin::class);
    $this->getHttpClientBuilder()->addPlugin(new AddHostPlugin($uri));
  }

  public function getHttpClient(): HttpMethodsClientInterface {
    return $this->getHttpClientBuilder()->getHttpClient();
  }

  protected function getHttpClientBuilder(): Builder {
    return $this->httpClientBuilder;
  }

  private function getDefaultHeaders(): array {
    return [
      "Duffel-Version" => $this->apiVersion,
      "Content-Type" => "application/json",
      "Accept-Encoding" => "gzip, deflate, br",
      "User-Agent" => $this->getUserAgent(),
    ];
  }

  private function getUserAgent(): string {
    return "Duffel/" . $this->apiVersion . " duffel_api_php/" . self::VERSION;
  }
}
