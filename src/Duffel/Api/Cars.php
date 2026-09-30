<?php

declare(strict_types=1);

namespace Duffel\Api;

use Duffel\Client;
use Duffel\Api\Cars\Bookings;
use Duffel\Api\Cars\Quotes;
use Duffel\Api\Cars\Searches;

class Cars {
  private ?Searches $searches = null;
  private ?Quotes $quotes = null;
  private ?Bookings $bookings = null;

  public function __construct(private Client $client) {}

  public function searches(): Searches {
    return $this->searches ??= new Searches($this->client);
  }

  public function quotes(): Quotes {
    return $this->quotes ??= new Quotes($this->client);
  }

  public function bookings(): Bookings {
    return $this->bookings ??= new Bookings($this->client);
  }
}
