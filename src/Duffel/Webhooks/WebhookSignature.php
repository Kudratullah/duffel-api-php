<?php

declare(strict_types=1);

namespace Duffel\Webhooks;

final class WebhookSignature {
  /**
   * Verify the authenticity of an incoming Duffel webhook payload.
   *
   * @param string $payload Raw JSON body string
   * @param string $signature Header value (X-Duffel-Signature or t=...,v1=...)
   * @param string $secret Webhook secret key from Duffel dashboard
   * @return bool True if valid, false otherwise
   */
  public static function verify(string $payload, string $signature, string $secret): bool {
    if ('' === \trim($payload) || '' === \trim($signature) || '' === \trim($secret)) {
      return false;
    }

    // Handle t=timestamp,v1=signature header format if present
    $targetSignature = $signature;
    if (\str_contains($signature, 'v1=')) {
      $parts = \explode(',', $signature);
      $timestamp = '';
      $v1 = '';

      foreach ($parts as $part) {
        [$key, $val] = \explode('=', \trim($part), 2) + ['', ''];
        if ('t' === $key) {
          $timestamp = $val;
        } elseif ('v1' === $key) {
          $v1 = $val;
        }
      }

      if ('' !== $timestamp && '' !== $v1) {
        $signedPayload = $timestamp . '.' . $payload;
        $computed = \hash_hmac('sha256', $signedPayload, $secret);
        return \hash_equals($computed, $v1);
      }

      $targetSignature = $v1;
    }

    $expectedSignature = \hash_hmac('sha256', $payload, $secret);
    return \hash_equals($expectedSignature, $targetSignature);
  }
}
