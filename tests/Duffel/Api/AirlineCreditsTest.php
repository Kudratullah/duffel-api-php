<?php

declare(strict_types=1);

namespace Duffel\Tests\Api;

use Duffel\Api\AirlineCredits;
use Duffel\Client;
use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;

class AirlineCreditsTest extends TestCase {
  private $mock;
  private $stub;

  public function setUp(): void {
    $this->mock = $this->createMock(HttpMethodsClientInterface::class);
    $this->stub = $this->createStub(Client::class);
    $this->stub->method('getHttpClient')->willReturn($this->mock);
  }

  public function testAllCallsGet(): void {
    $this->mock->expects($this->once())
               ->method('get')
               ->with($this->equalTo('/air/airline_credits'));

    $api = new AirlineCredits($this->stub);
    $api->all();
  }

  public function testShowCallsGet(): void {
    $this->mock->expects($this->once())
               ->method('get')
               ->with($this->equalTo('/air/airline_credits/credit_123'));

    $api = new AirlineCredits($this->stub);
    $api->show('credit_123');
  }

  public function testCreateCallsPost(): void {
    $this->mock->expects($this->once())
               ->method('post')
               ->with(
                 $this->equalTo('/air/airline_credits'),
                 $this->equalTo(['Content-Type' => 'application/json']),
                 $this->equalTo('{"data":{"code":"CREDIT123"}}')
               );

    $api = new AirlineCredits($this->stub);
    $api->create(['code' => 'CREDIT123']);
  }
}
