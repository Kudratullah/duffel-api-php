<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Duffel\Client;

echo "Duffel Cars API - search and quote example\n";

$client = new Client();
$client->setAccessToken(getenv('DUFFEL_ACCESS_TOKEN'));

// 1. Search for rental cars
echo "Searching for rental cars at LHR...\n";
$search = $client->cars()->searches()->create([
  'pickup_location' => ['iata_code' => 'LHR'],
  'dropoff_location' => ['iata_code' => 'LHR'],
  'pickup_datetime' => (new DateTime())->add(new DateInterval('P30D'))->format('Y-m-d\TH:i:s\Z'),
  'dropoff_datetime' => (new DateTime())->add(new DateInterval('P35D'))->format('Y-m-d\TH:i:s\Z'),
  'driver_age' => 30,
]);

if (isset($search['id'])) {
  echo sprintf("Created car search %s\n", $search['id']);
} else if (isset($search['errors'])) {
  echo sprintf("Cars API response: %s (Ensure Cars is enabled on your account)\n", $search['errors'][0]['message'] ?? 'Unknown error');
}
