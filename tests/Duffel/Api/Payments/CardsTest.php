<?php

declare(strict_types=1);

namespace Duffel\Tests\Api\Payments;

use Duffel\Api\Payments\Cards;
use Duffel\Client;
use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;

class CardsTest extends TestCase {
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
                 $this->equalTo('/payments/cards'),
                 $this->equalTo(['Content-Type' => 'application/json']),
                 $this->equalTo('{"data":{"token":"card_tok_123"}}')
               );

    $api = new Cards($this->stub);
    $api->create(['token' => 'card_tok_123']);
  }

  public function testDeleteCardCallsDelete(): void {
    $this->mock->expects($this->once())
               ->method('delete')
               ->with($this->equalTo('/payments/cards/card_123'));

    $api = new Cards($this->stub);
    $api->deleteCard('card_123');
  }
}
