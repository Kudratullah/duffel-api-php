<?php

declare(strict_types=1);

namespace Duffel\Tests\Api\Stays;

use Duffel\Api\Stays\Searches;
use Duffel\Client;
use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;

class SearchesTest extends TestCase {
  private $mock;
  private $stub;

  public function setUp(): void {
    $this->mock = $this->createMock(HttpMethodsClientInterface::class);
    $this->stub = $this->createStub(Client::class);
    $this->stub->method('getHttpClient')->willReturn($this->mock);
  }

  public function testCreateCallsPost(): void {
    $this->mock->expects($this->once())
               ->method('post')
               ->with(
                 $this->equalTo('/stays/searches'),
                 $this->equalTo(['Content-Type' => 'application/json']),
                 $this->equalTo('{"data":{"rooms":1}}')
               );

    $api = new Searches($this->stub);
    $api->create(['rooms' => 1]);
  }

  public function testFetchAllRatesCallsGet(): void {
    $this->mock->expects($this->once())
               ->method('get')
               ->with($this->equalTo('/stays/search_results/sres_123/rates'));

    $api = new Searches($this->stub);
    $api->fetchAllRates('sres_123');
  }
}
