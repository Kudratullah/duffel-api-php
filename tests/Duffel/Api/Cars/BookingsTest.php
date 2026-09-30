<?php

declare(strict_types=1);

namespace Duffel\Tests\Api\Cars;

use Duffel\Api\Cars\Bookings;
use Duffel\Client;
use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;

class BookingsTest extends TestCase {
  private $mock;
  private $stub;

  public function setUp(): void {
    $this->mock = $this->createMock(HttpMethodsClientInterface::class);
    $this->stub = $this->createStub(Client::class);
    $this->stub->method('getHttpClient')->willReturn($this->mock);
  }

  public function testShowCallsGet(): void {
    $this->mock->expects($this->once())
               ->method('get')
               ->with($this->equalTo('/cars/bookings/car_bkg_123'));

    $api = new Bookings($this->stub);
    $api->show('car_bkg_123');
  }

  public function testCreateCallsPost(): void {
    $this->mock->expects($this->once())
               ->method('post')
               ->with(
                 $this->equalTo('/cars/bookings'),
                 $this->equalTo(['Content-Type' => 'application/json']),
                 $this->equalTo('{"data":{"quote_id":"car_quo_123"}}')
               );

    $api = new Bookings($this->stub);
    $api->create(['quote_id' => 'car_quo_123']);
  }

  public function testCancelCallsPost(): void {
    $this->mock->expects($this->once())
               ->method('post')
               ->with($this->equalTo('/cars/bookings/car_bkg_123/actions/cancel'));

    $api = new Bookings($this->stub);
    $api->cancel('car_bkg_123');
  }
}
