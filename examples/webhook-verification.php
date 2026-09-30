<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Duffel\Webhooks\WebhookSignature;

echo "Duffel Webhook Signature Verification Example\n";

$payload = '{"event":{"type":"order.created","id":"evt_0000A123"},"data":{"id":"ord_0000B456"}}';
$secret = 'whsec_test_secret_123';

// Generate simulated signature header
$timestamp = (string) time();
$v1 = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);
$signatureHeader = "t={$timestamp},v1={$v1}";

$isValid = WebhookSignature::verify($payload, $signatureHeader, $secret);

if ($isValid) {
  echo "✅ Webhook signature verified successfully!\n";
} else {
  echo "❌ Invalid webhook signature!\n";
}
