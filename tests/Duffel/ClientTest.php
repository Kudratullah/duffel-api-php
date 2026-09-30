<?php

declare(strict_types=1);

namespace Duffel\Tests;

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
use Duffel\Client;
use Duffel\Exception\InvalidAccessTokenException;
use Duffel\HttpClient\Builder;
use Http\Client\Common\HttpMethodsClient;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase {
  private Client $subject;

  public function setUp(): void {
    $this->subject = new Client(new Builder());
  }

  public function testCreatesNewClient(): void {
    $this->assertInstanceOf(Client::class, $this->subject);
    $this->assertInstanceOf(HttpMethodsClient::class, $this->subject->getHttpClient());
  }

  public function testNewSetsDefaultApiVersion(): void {
    $this->assertSame('v2', $this->subject->getApiVersion());
  }

  public function testNewSetsDefaultAccessToken(): void {
    $this->assertSame('', $this->subject->getAccessToken());
  }

  public function testDebugInfoRedactsAccessToken(): void {
    $this->subject->setAccessToken('duffel_test_secret_123');
    $debug = $this->subject->__debugInfo();

    $this->assertSame('[REDACTED]', $debug['accessToken']);
    $this->assertSame('v2', $debug['apiVersion']);
  }

  public function testAircraftUsesApiClass(): void {
    $this->assertInstanceOf(Aircraft::class, $this->subject->aircraft());
  }

  public function testAirlinesUsesApiClass(): void {
    $this->assertInstanceOf(Airlines::class, $this->subject->airlines());
  }

  public function testAirportsUsesApiClass(): void {
    $this->assertInstanceOf(Airports::class, $this->subject->airports());
  }

  public function testOfferRequestsUsesApiClass(): void {
    $this->assertInstanceOf(OfferRequests::class, $this->subject->offerRequests());
  }

  public function testOffersUsesApiClass(): void {
    $this->assertInstanceOf(Offers::class, $this->subject->offers());
  }

  public function testOrdersUsesApiClass(): void {
    $this->assertInstanceOf(Orders::class, $this->subject->orders());
  }

  public function testStaysUsesApiClass(): void {
    $this->assertInstanceOf(Stays::class, $this->subject->stays());
  }

  public function testCarsUsesApiClass(): void {
    $this->assertInstanceOf(Cars::class, $this->subject->cars());
  }

  public function testIdentityUsesApiClass(): void {
    $this->assertInstanceOf(Identity::class, $this->subject->identity());
  }

  public function testPlacesUsesApiClass(): void {
    $this->assertInstanceOf(Places::class, $this->subject->places());
  }

  public function testCitiesUsesApiClass(): void {
    $this->assertInstanceOf(Cities::class, $this->subject->cities());
  }

  public function testLoyaltyProgrammesUsesApiClass(): void {
    $this->assertInstanceOf(LoyaltyProgrammes::class, $this->subject->loyaltyProgrammes());
  }

  public function testSetAccessTokenChangesValue(): void {
    $this->subject->setAccessToken('some-token');
    $this->assertSame('some-token', $this->subject->getAccessToken());
  }

  public function testSetAccessTokenWithEmptyStringThrowsException(): void {
    $this->expectException(InvalidAccessTokenException::class);
    $this->subject->setAccessToken('   ');
  }

  public function testSetApiVersionChangesValue(): void {
    $this->subject->setApiVersion('v2');
    $this->assertSame('v2', $this->subject->getApiVersion());
  }
}
