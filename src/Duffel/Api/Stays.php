<?php

declare(strict_types=1);

namespace Duffel\Api;

use Duffel\Client;
use Duffel\Api\Stays\Accommodation;
use Duffel\Api\Stays\AccommodationReviews;
use Duffel\Api\Stays\BookingPaymentInstructions;
use Duffel\Api\Stays\Bookings;
use Duffel\Api\Stays\Brands;
use Duffel\Api\Stays\Chains;
use Duffel\Api\Stays\LoyaltyProgrammes;
use Duffel\Api\Stays\NegotiatedRates;
use Duffel\Api\Stays\Quotes;
use Duffel\Api\Stays\Searches;

class Stays {
  private ?Searches $searches = null;
  private ?Quotes $quotes = null;
  private ?Bookings $bookings = null;
  private ?Accommodation $accommodation = null;
  private ?AccommodationReviews $accommodationReviews = null;
  private ?BookingPaymentInstructions $bookingPaymentInstructions = null;
  private ?Brands $brands = null;
  private ?Chains $chains = null;
  private ?LoyaltyProgrammes $loyaltyProgrammes = null;
  private ?NegotiatedRates $negotiatedRates = null;

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

  public function accommodation(): Accommodation {
    return $this->accommodation ??= new Accommodation($this->client);
  }

  public function accommodationReviews(): AccommodationReviews {
    return $this->accommodationReviews ??= new AccommodationReviews($this->client);
  }

  public function bookingPaymentInstructions(): BookingPaymentInstructions {
    return $this->bookingPaymentInstructions ??= new BookingPaymentInstructions($this->client);
  }

  public function brands(): Brands {
    return $this->brands ??= new Brands($this->client);
  }

  public function chains(): Chains {
    return $this->chains ??= new Chains($this->client);
  }

  public function loyaltyProgrammes(): LoyaltyProgrammes {
    return $this->loyaltyProgrammes ??= new LoyaltyProgrammes($this->client);
  }

  public function negotiatedRates(): NegotiatedRates {
    return $this->negotiatedRates ??= new NegotiatedRates($this->client);
  }
}
