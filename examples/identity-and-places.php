<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Duffel\Client;

echo "Duffel Places & Identity API Example\n";

$client = new Client();
$client->setAccessToken(getenv('DUFFEL_ACCESS_TOKEN'));

// 1. Search place suggestions
echo "Fetching place suggestions for 'London'...\n";
$suggestions = $client->places()->suggestions('London');

if (is_array($suggestions) && !empty($suggestions)) {
  echo sprintf("Found %d place suggestions. First match: %s (%s)\n",
    count($suggestions),
    $suggestions[0]['name'] ?? 'N/A',
    $suggestions[0]['iata_code'] ?? 'N/A'
  );
}

// 2. Customer User Groups
echo "Listing customer user groups...\n";
$groups = $client->identity()->userGroups()->all();

if (is_array($groups)) {
  echo sprintf("Got %d user groups\n", count($groups));
}
