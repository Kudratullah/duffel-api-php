---
name: duffel-travel-api
description: Search, book, and manage flights, stays (hotels), and cars using the Duffel API PHP SDK (v2). Use when implementing or debugging flight searches, hotel bookings, seat selection, extra baggage, order cancellations, or webhook verifications.
---

# Duffel Travel API (PHP SDK v2) Skill

This skill guides AI agents on how to use the `duffel/api` PHP SDK (`Duffel\Client`) to perform travel searches, bookings, and operations across Flights, Stays, Cars, and Places.

## Key Capabilities & Requirements
- **PHP Version**: 8.4+
- **Duffel API Version**: `v2`
- **Environment variable for Auth**: `DUFFEL_ACCESS_TOKEN` (starts with `duffel_test_` for sandbox or `duffel_live_` for production).

---

## 1. Quick Setup & Client Initialization

```php
use Duffel\Client;

$client = new Client();
$client->setAccessToken(getenv('DUFFEL_ACCESS_TOKEN'));
```

---

## 2. Flight Workflows

### Search Flights (`OfferRequests`) & List Offers (`Offers`)

```php
$offerRequest = $client->offerRequests()->create(
    cabin_class: "economy",
    passengers: [
        ["type" => "adult"],
    ],
    slices: [
        [
            "origin" => "LHR",
            "destination" => "JFK",
            "departure_date" => "2026-11-15",
        ]
    ]
);

$offers = $client->offers()->all($offerRequest['id']);
$selectedOffer = $offers[0];
```

### Price Offer & Include Available Services (Seats & Baggage)

```php
$pricedOffer = $client->offers()->show($selectedOffer['id'], returnAvailableServices: true);
```

### Create Order & Book Flight (`Orders`)

```php
$order = $client->orders()->create([
    'selected_offers' => [$pricedOffer['id']],
    'payments' => [
        [
            'type' => 'balance',
            'amount' => $pricedOffer['total_amount'],
            'currency' => $pricedOffer['total_currency'],
        ]
    ],
    'passengers' => [
        [
            'id' => $pricedOffer['passengers'][0]['id'],
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
```

### Hold Order (Pay Later)

To hold an order without immediate payment, omit the `payments` array:

```php
$holdOrder = $client->orders()->create([
    'selected_offers' => [$pricedOffer['id']],
    'passengers' => [...]
]);

// Pay for held order later:
$payment = $client->payments()->create(
    orderId: $holdOrder['id'],
    payment: [
        'type' => 'balance',
        'amount' => $holdOrder['total_amount'],
        'currency' => $holdOrder['total_currency'],
    ]
);
```

### Cancel Order & Confirm Refund Quote

```php
$cancellation = $client->orderCancellations()->create($order['id']);
// Review cancellation['refund_amount']
$confirmedCancellation = $client->orderCancellations()->confirm($cancellation['id']);
```

---

## 3. Stays (Hotels & Accommodation) Workflows

```php
// 1. Search Stays
$search = $client->stays()->searches()->create([
    'location' => [
        'geographic_coordinates' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ]
    ],
    'check_in_date' => '2026-12-01',
    'check_out_date' => '2026-12-05',
    'guests' => [['type' => 'adult']],
    'rooms' => 1,
]);

// 2. Fetch Rates for Search Result
$rates = $client->stays()->searches()->fetchAllRates($search['results'][0]['id']);

// 3. Create Quote for Rate
$quote = $client->stays()->quotes()->create($rates[0]['id']);

// 4. Create Booking
$booking = $client->stays()->bookings()->create([
    'quote_id' => $quote['id'],
    'guests' => [
        [
            'given_name' => 'John',
            'family_name' => 'Doe',
            'email' => 'john@example.com',
        ]
    ],
    'email' => 'john@example.com',
    'phone_number' => '+12025550143',
]);
```

---

## 4. Cursor Pagination Helper

Use lazy generators for iterating through full lists (airports, airlines, orders) without manual page loops:

```php
foreach ($client->airports()->iterate('/air/airports', ['limit' => 100]) as $airport) {
    echo $airport['iata_code'] . ': ' . $airport['name'] . "\n";
}
```

---

## 5. Secure Webhook Verification

```php
use Duffel\Webhooks\WebhookSignature;

$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_DUFFEL_SIGNATURE'] ?? '';
$secret = getenv('DUFFEL_WEBHOOK_SECRET');

if (!WebhookSignature::verify($payload, $signature, $secret)) {
    http_response_code(401);
    exit('Invalid signature');
}
```

---

## Error Handling & Troubleshooting
- All API errors thrown by `Duffel\HttpClient\JsonArray` or HTTP errors return parsed Duffel error messages with `[x-request-id]: ErrorType (code): message`.
- Test mode tokens (`duffel_test_...`) interact with Duffel sandbox. Use `duffel-airways` for test bookings.
