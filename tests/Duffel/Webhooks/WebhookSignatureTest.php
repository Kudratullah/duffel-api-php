<?php

declare(strict_types=1);

namespace Duffel\Tests\Webhooks;

use Duffel\Webhooks\WebhookSignature;
use PHPUnit\Framework\TestCase;

class WebhookSignatureTest extends TestCase {
  public function testVerifiesValidRawHmacSignature(): void {
    $payload = '{"event":"order.created"}';
    $secret = 'whsec_test_secret_123';
    $expectedSignature = \hash_hmac('sha256', $payload, $secret);

    $this->assertTrue(WebhookSignature::verify($payload, $expectedSignature, $secret));
  }

  public function testVerifiesValidHeaderFormatSignature(): void {
    $payload = '{"event":"order.created"}';
    $secret = 'whsec_test_secret_123';
    $timestamp = '1600000000';
    $signedPayload = $timestamp . '.' . $payload;
    $v1Signature = \hash_hmac('sha256', $signedPayload, $secret);
    $header = "t={$timestamp},v1={$v1Signature}";

    $this->assertTrue(WebhookSignature::verify($payload, $header, $secret));
  }

  public function testRejectsInvalidSignature(): void {
    $payload = '{"event":"order.created"}';
    $secret = 'whsec_test_secret_123';
    $invalidSignature = 'invalid_signature_hash';

    $this->assertFalse(WebhookSignature::verify($payload, $invalidSignature, $secret));
  }

  public function testReturnsFalseForEmptyInputs(): void {
    $this->assertFalse(WebhookSignature::verify('', 'sig', 'secret'));
    $this->assertFalse(WebhookSignature::verify('body', '', 'secret'));
    $this->assertFalse(WebhookSignature::verify('body', 'sig', ''));
  }
}
