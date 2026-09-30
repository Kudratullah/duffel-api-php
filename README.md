# Duffel API PHP SDK (v2 Modernized)

[![Tests](https://github.com/duffelhq/duffel-api-php/actions/workflows/tests.yml/badge.svg)](https://github.com/duffelhq/duffel-api-php/actions/workflows/tests.yml)
[![PHP Version](https://img.shields.io/badge/php-%5E8.4-777BB4.svg)](https://php.net)
[![Duffel API Version](https://img.shields.io/badge/Duffel_API-v2-purple.svg)](https://duffel.com/docs/api)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

A modern PHP 8.4+ SDK for the [Duffel API v2](https://duffel.com/docs/api). Search, book, and manage Flights, Stays (Hotel Accommodation), Cars, Places, and Identity.

---

## Features

- **PHP 8.4+ Ready**: Strict typing, native JSON exceptions, performance optimizations, and PHPUnit 11 test suite.
- **Duffel API v2**: Configured out of the box for Duffel API `v2`.
- **Complete Travel API Coverage**:
  - ✈️ **Flights**: Offer Requests, Offers (with pricing & upsell), Orders, Seat Maps, Payments, Cancellations, Order Changes, Airline Credits, Airline Initiated Changes, Partial & Batch Offer Requests.
  - 🏨 **Stays**: Hotel search, rates, quotes, bookings, accommodation details, brand & chain metadata.
  - 🚗 **Cars**: Search, quotes, and rental bookings.
  - 📍 **Places & Identity**: Location suggestions, city search, customer user management, component client keys.
- **Security & Safety**: Automatic token redaction in `__debugInfo()` stack traces, secure HTTP defaults, and HMAC-SHA256 timing-safe **Webhook Signature Verification**.
- **Lazy Cursor Pagination**: Generator-based iteration for large lists (`$client->airports()->iterate(...)`).
- **AI Agent Skill**: Includes a built-in AI Agent Skill (`skills/duffel-travel-api/SKILL.md`) for autonomous coding agents.

---

## Requirements

- PHP **^8.4**
- A Duffel API access token (`duffel_test_...` or `duffel_live_...` from your [Duffel Dashboard](https://duffel.com/dashboard))

---

## Installation

Install via Composer:

```bash
composer require duffel/api guzzlehttp/guzzle:^7.9
```

---

## Usage

### 1. Initialize Client

```php
use Duffel\Client;

$client = new Client();
$client->setAccessToken(getenv('DUFFEL_ACCESS_TOKEN'));
```

### 2. Search & Book Flights

```php
// 1. Create Offer Request
$offerRequest = $client->offerRequests()->create(
    cabin_class: "economy",
    passengers: [["type" => "adult"]],
    slices: [
        [
            "origin" => "LHR",
            "destination" => "JFK",
            "departure_date" => "2026-11-20"
        ]
    ]
);

// 2. Fetch Offers
$offers = $client->offers()->all($offerRequest['id']);
$selectedOffer = $offers[0];

// 3. Create Order
$order = $client->orders()->create([
    'selected_offers' => [$selectedOffer['id']],
    'payments' => [
        [
            'type' => 'balance',
            'amount' => $selectedOffer['total_amount'],
            'currency' => $selectedOffer['total_currency'],
        ]
    ],
    'passengers' => [
        [
            'id' => $selectedOffer['passengers'][0]['id'],
            'title' => 'mr',
            'gender' => 'm',
            'given_name' => 'John',
            'family_name' => 'Doe',
            'born_on' => '1990-01-01',
            'phone_number' => '+447123456789',
            'email' => 'john.doe@example.com',
        ]
    ]
]);

echo "Booked flight! Order ID: " . $order['id'];
```

### 3. Search & Book Stays (Hotels)

```php
$search = $client->stays()->searches()->create([
    'location' => [
        'geographic_coordinates' => ['latitude' => 40.7128, 'longitude' => -74.0060]
    ],
    'check_in_date' => '2026-12-01',
    'check_out_date' => '2026-12-05',
    'guests' => [['type' => 'adult']],
    'rooms' => 1,
]);

$rates = $client->stays()->searches()->fetchAllRates($search['results'][0]['id']);
$quote = $client->stays()->quotes()->create($rates[0]['id']);

$booking = $client->stays()->bookings()->create([
    'quote_id' => $quote['id'],
    'guests' => [['given_name' => 'John', 'family_name' => 'Doe', 'email' => 'john@example.com']],
    'email' => 'john@example.com',
    'phone_number' => '+12025550143',
]);
```

### 4. Lazy Cursor Pagination

Iterate smoothly over paginated endpoints without manual `after` cursor loops:

```php
foreach ($client->airports()->iterate('/air/airports', ['limit' => 50]) as $airport) {
    echo $airport['name'] . "\n";
}
```

### 5. Verify Webhook Signatures

```php
use Duffel\Webhooks\WebhookSignature;

$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_DUFFEL_SIGNATURE'] ?? '';
$secret = getenv('DUFFEL_WEBHOOK_SECRET');

if (WebhookSignature::verify($payload, $signature, $secret)) {
    // Process webhook event securely
}
```

---

## Examples

Run any of the working scripts in the [`examples/`](./examples) directory:

- `examples/search-and-book-one-way.php`
- `examples/stays-search-and-book.php`
- `examples/cursor-pagination.php`
- `examples/webhook-verification.php`

---

## License

This package is licensed under the [MIT License](LICENSE).
