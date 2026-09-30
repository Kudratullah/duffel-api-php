<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Duffel\Client;

echo "Duffel Stays API - search and book example\n";

$client = new Client();
$client->setAccessToken(getenv('DUFFEL_ACCESS_TOKEN'));

// 1. Search for stays
echo "Searching for hotel stays in New York...\n";
$search = $client->stays()->searches()->create([
  'location' => [
    'geographic_coordinates' => [
      'latitude' => 40.7128,
      'longitude' => -74.0060,
    ]
  ],
  'check_in_date' => (new DateTime())->add(new DateInterval('P30D'))->format('Y-m-d'),
  'check_out_date' => (new DateTime())->add(new DateInterval('P35D'))->format('Y-m-d'),
  'guests' => [['type' => 'adult']],
  'rooms' => 1,
]);

echo sprintf("Created search %s\n", $search['id']);

if (!empty($search['results'])) {
  $searchResult = $search['results'][0];
  echo sprintf("Selected search result %s\n", $searchResult['id']);

  // 2. Fetch rates
  $rates = $client->stays()->searches()->fetchAllRates($searchResult['id']);
  if (!empty($rates)) {
    $selectedRate = $rates[0];
    echo sprintf("Selected rate %s (%s %s)\n", $selectedRate['id'], $selectedRate['total_amount'], $selectedRate['total_currency']);

    // 3. Create quote
    $quote = $client->stays()->quotes()->create($selectedRate['id']);
    echo sprintf("Created quote %s\n", $quote['id']);

    // 4. Create booking
    $booking = $client->stays()->bookings()->create([
      'quote_id' => $quote['id'],
      'guests' => [
        [
          'given_name' => 'John',
          'family_name' => 'Doe',
          'email' => 'john.doe@example.com',
        ]
      ],
      'email' => 'john.doe@example.com',
      'phone_number' => '+12025550143',
    ]);

    echo sprintf("Successfully booked stay! Booking ID: %s, Reference: %s\n", $booking['id'], $booking['reference'] ?? 'N/A');
  }
}
