<?php

declare(strict_types=1);

namespace Duffel\Tests\Api;

use Duffel\Api\AbstractApi;
use Duffel\Client;
use Duffel\HttpClient\Builder;
use Http\Mock\Client as MockClient;
use PHPUnit\Framework\TestCase;

class AbstractApiTest extends TestCase {
  private Builder $builder;
  private Client $client;
  private MockClient $mock;
  private AbstractApi $subject;

  public function setUp(): void {
    $this->mock = new MockClient();
    $this->builder = new Builder($this->mock);
    $this->client = new Client($this->builder);
  }

  public function testConstructorRequiresClient(): void {
    $stub = new class($this->client) extends AbstractApi {};
    $this->assertIsObject($stub);
  }

  public function testGetAsResponseCallsHttpClientMethod(): void {
    $this->subject = new class($this->client) extends AbstractApi {
      public function testGet(string $uri, array $params = [], array $headers = []): mixed {
        return $this->get($uri, $params, $headers);
      }
    };

    $this->subject->testGet('/some-get-uri');

    $requests = $this->mock->getRequests();
    $this->assertEquals(1, count($requests));

    $request = array_shift($requests);
    $this->assertEquals('GET', $request->getMethod());
    $this->assertEquals('/some-get-uri', $request->getUri()->getPath());
    $this->assertEquals('', $request->getUri()->getQuery());
    $this->assertContains('application/json', $request->getHeader('Content-Type'));
  }

  public function testGetAsResponseWithQueryParametersAppendsQueryString(): void {
    $this->subject = new class($this->client) extends AbstractApi {
      public function testGet(string $uri, array $params = [], array $headers = []): mixed {
        return $this->get($uri, $params, $headers);
      }
    };

    $this->subject->testGet('/some-get-uri', ['limit' => 50, 'after' => 'cursor_123']);

    $requests = $this->mock->getRequests();
    $this->assertEquals(1, count($requests));

    $request = array_shift($requests);
    $this->assertEquals('GET', $request->getMethod());
    $this->assertEquals('/some-get-uri', $request->getUri()->getPath());
    $this->assertEquals('limit=50&after=cursor_123', $request->getUri()->getQuery());
    $this->assertContains('application/json', $request->getHeader('Content-Type'));
  }

  public function testPostAsResponseCallsHttpClientMethod(): void {
    $this->subject = new class($this->client) extends AbstractApi {
      public function testPost(string $uri, array $params = [], array $headers = []): mixed {
        return $this->post($uri, $params, $headers);
      }
    };

    $this->subject->testPost('/some-post-uri', ['key' => 'value']);

    $requests = $this->mock->getRequests();
    $this->assertEquals(1, count($requests));

    $request = array_shift($requests);
    $this->assertEquals('POST', $request->getMethod());
    $this->assertEquals('/some-post-uri', $request->getUri()->getPath());
    $this->assertEquals('{"data":{"key":"value"}}', $request->getBody()->__toString());
    $this->assertContains('application/json', $request->getHeader('Content-Type'));
  }

  public function testPatchAsResponseCallsHttpClientMethod(): void {
    $this->subject = new class($this->client) extends AbstractApi {
      public function testPatch(string $uri, array $params = [], array $headers = []): mixed {
        return $this->patch($uri, $params, $headers);
      }
    };

    $this->subject->testPatch('/some-patch-uri', ['key' => 'value']);

    $requests = $this->mock->getRequests();
    $this->assertEquals(1, count($requests));

    $request = array_shift($requests);
    $this->assertEquals('PATCH', $request->getMethod());
    $this->assertEquals('/some-patch-uri', $request->getUri()->getPath());
    $this->assertEquals('{"data":{"key":"value"}}', $request->getBody()->__toString());
    $this->assertContains('application/json', $request->getHeader('Content-Type'));
  }

  public function testPutAsResponseCallsHttpClientMethod(): void {
    $this->subject = new class($this->client) extends AbstractApi {
      public function testPut(string $uri, array $params = [], array $headers = []): mixed {
        return $this->put($uri, $params, $headers);
      }
    };

    $this->subject->testPut('/some-put-uri', ['key' => 'value']);

    $requests = $this->mock->getRequests();
    $this->assertEquals(1, count($requests));

    $request = array_shift($requests);
    $this->assertEquals('PUT', $request->getMethod());
    $this->assertEquals('/some-put-uri', $request->getUri()->getPath());
    $this->assertEquals('{"data":{"key":"value"}}', $request->getBody()->__toString());
    $this->assertContains('application/json', $request->getHeader('Content-Type'));
  }

  public function testDeleteAsResponseCallsHttpClientMethod(): void {
    $this->subject = new class($this->client) extends AbstractApi {
      public function testDelete(string $uri, array $params = [], array $headers = []): mixed {
        return $this->delete($uri, $params, $headers);
      }
    };

    $this->subject->testDelete('/some-delete-uri', ['key' => 'value']);

    $requests = $this->mock->getRequests();
    $this->assertEquals(1, count($requests));

    $request = array_shift($requests);
    $this->assertEquals('DELETE', $request->getMethod());
    $this->assertEquals('/some-delete-uri', $request->getUri()->getPath());
    $this->assertEquals('{"data":{"key":"value"}}', $request->getBody()->__toString());
    $this->assertContains('application/json', $request->getHeader('Content-Type'));
  }
}
