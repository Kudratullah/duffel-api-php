<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Duffel\Client;

echo "Duffel API - Cursor Pagination Generator Example\n";

$client = new Client();
$client->setAccessToken(getenv('DUFFEL_ACCESS_TOKEN'));

$count = 0;
echo "Iterating through airports using generator...\n";

foreach ($client->airports()->iterate('/air/airports', ['limit' => 20]) as $airport) {
  $count++;
  echo sprintf("[%d] %s (%s) - %s\n", $count, $airport['name'], $airport['iata_code'] ?? 'N/A', $airport['city_name'] ?? '');

  if ($count >= 10) {
    echo "Reached sample limit of 10 items.\n";
    break;
  }
}
