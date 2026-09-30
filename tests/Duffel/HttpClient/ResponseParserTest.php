<?php

declare(strict_types=1);

namespace Duffel\Tests\HttpClient;

use Duffel\HttpClient\ResponseParser;
use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ResponseParserTest extends TestCase {
  public function testConstantContainsExpectedContentTypeHeader(): void {
    $this->assertSame('Content-Type', ResponseParser::CONTENT_TYPE_HEADER);
  }

  public function testConstantContainsExpectedJsonContentType(): void {
    $this->assertSame('application/json', ResponseParser::JSON_CONTENT_TYPE);
  }

  public function testGetContentAsJsonWithoutDataKey(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('{"some": {"keys": ["with", "values"]} }'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');

    $this->assertSame(["some" => ["keys" => ["with", "values"]]], ResponseParser::getContent($stub));
  }

  public function testGetContentAsJsonWithDataKey(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('{"data": {"some": {"keys": ["with", "values"]} } }'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');

    $this->assertSame(["some" => ["keys" => ["with", "values"]]], ResponseParser::getContent($stub));
  }

  public function testGetContentWithNilBodyAndContentTypeAsJson(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor(''));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');

    $this->assertSame('', ResponseParser::getContent($stub));
  }

  public function testGetContentWithNullBodyAndContentTypeAsJson(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('null'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');

    $this->assertSame('null', ResponseParser::getContent($stub));
  }

  public function testGetContentWithTrueBodyAndContentTypeAsJson(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('true'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');

    $this->assertSame('true', ResponseParser::getContent($stub));
  }

  public function testGetContentWithFalseBodyAndContentTypeAsJson(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('false'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');

    $this->assertSame('false', ResponseParser::getContent($stub));
  }

  public function testGetErrorMessageTransformsList(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('{
         "errors": [
           {
             "code": "missing_authorization_header",
             "documentation_url": "https://duffel.com/docs/api/overview/errors",
             "message": "The \'Authorization\' header needs to be set and contain a valid API token.",
             "title": "Missing authorization header",
             "type": "authentication_error"
           }
         ],
         "meta": {
           "request_id": "FZW0H3HdJwKk5HMAAKxB",
           "status": 401
         }
       }'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');
    $stub->method('getHeader')
         ->with('x-request-id')
         ->willReturn(['some-request-id']);

    $this->assertSame(
      '[some-request-id]: Missing authorization header (missing_authorization_header): The \'Authorization\' header needs to be set and contain a valid API token.',
      ResponseParser::getErrorMessage($stub)
    );
  }

  public function testGetErrorMessageWhenRequestIdIsMissingReturnsUnwrappedMessage(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('{
         "errors": [
           {
             "code": "missing_authorization_header",
             "documentation_url": "https://duffel.com/docs/api/overview/errors",
             "message": "The \'Authorization\' header needs to be set and contain a valid API token.",
             "title": "Missing authorization header",
             "type": "authentication_error"
           }
         ]
       }'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');
    $stub->method('getHeader')
         ->with('x-request-id')
         ->willReturn([]);

    $this->assertSame(
      'Missing authorization header (missing_authorization_header): The \'Authorization\' header needs to be set and contain a valid API token.',
      ResponseParser::getErrorMessage($stub)
    );
  }

  public function testGetErrorMessageWhenJsonDecodeFailsReturnsNull(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('invalid json'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');

    $this->assertNull(ResponseParser::getErrorMessage($stub));
  }

  public function testGetErrorMessageWhenJsonIsStringReturnsNull(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('"hello"'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');

    $this->assertNull(ResponseParser::getErrorMessage($stub));
  }

  public function testGetErrorMessageWhenTextIsStringReturnsNull(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('plain text'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('text/plain');

    $this->assertNull(ResponseParser::getErrorMessage($stub));
  }

  public function testGetErrorMessageWhenJsonIsObjectWithoutErrorsKeyReturnsNull(): void {
    $stub = $this->createMock(ResponseInterface::class);
    $stub->method('getBody')
         ->willReturn(Utils::streamFor('{"foo": "bar"}'));
    $stub->method('getHeaderLine')
         ->with('Content-Type')
         ->willReturn('application/json');

    $this->assertNull(ResponseParser::getErrorMessage($stub));
  }
}
