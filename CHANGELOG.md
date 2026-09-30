# Changelog

All notable changes to `duffel/api` for PHP will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [2.0.0] - 2026-09-30

### Added
- **PHP 8.4+ Support**: Bumped minimum required PHP version to `^8.4` with strict typing, constructor property promotion, typed properties, and modern PSR interface implementations.
- **Duffel API v2**: Default API version header updated from `v1` (sunset in Jan 2025) to `v2`.
- **Stays (Hotel Accommodation) API**: Full support for searching, rate fetching, quote creation, booking, accommodation metadata, reviews (`AccommodationReviews`), payment instructions (`BookingPaymentInstructions`), and negotiated corporate rates (`NegotiatedRates`).
- **Cars API**: Full support for car rental searches, quotes, and bookings (`$client->cars()`).
- **Payments & Cards API**: Added support for saved payment cards (`$client->payments()->cards()`) and 3D Secure session creation (`$client->payments()->threeDSecureSession()`).
- **Flights API additions**:
  - `AirlineCredits` (`$client->airlineCredits()`)
  - `AirlineInitiatedChanges` (`$client->airlineInitiatedChanges()`)
  - `PartialOfferRequests` (`$client->partialOfferRequests()`)
  - `BatchOfferRequests` (`$client->batchOfferRequests()`)
- **Places & Identity APIs**:
  - Places suggestion endpoint (`$client->places()`)
  - Cities list/show endpoint (`$client->cities()`)
  - Customer Users & User Groups (`$client->identity()->users()`, `$client->identity()->userGroups()`)
  - Component Client Keys (`$client->identity()->componentClientKeys()`)
- **Webhooks & Security**:
  - `#[\SensitiveParameter]` attributes added across all API access tokens, card details, payment secrets, user info, and order payload parameters.
  - Cryptographically timing-attack safe `WebhookSignature::verify()` helper for HMAC-SHA256 signature verification.
  - Webhook deliveries and events management endpoints (`$client->webhookDeliveries()`, `$client->webhookEvents()`).
  - Access token redaction in `Client::__debugInfo()` (`[REDACTED]`) to prevent credential leaks in stack dumps.
- **Lazy Cursor Pagination**: Generator-based iteration helper (`$api->iterate()`) for cursor pagination without manual loop boilerplate.
- **Wire Compression**: Added `Accept-Encoding: gzip, deflate, br` headers.
- **AI Agent Skill**: Published AI Agent Skill definition in `skills/duffel-travel-api/SKILL.md`.
- **Examples**: Added runnable examples for stays (`stays-search-and-book.php`), cars (`cars-search-and-book.php`), identity/places (`identity-and-places.php`), cursor pagination (`cursor-pagination.php`), and webhook verification (`webhook-verification.php`).
- **Quality & Static Analysis**: PHPStan Level 8 clean, zero security advisories (`composer audit`), 100% PHPUnit 12 pass rate (113 tests).

### Fixed
- **Query Parameter Serialization Bug**: Fixed `AbstractApi::prepareUri` to append query parameters via `http_build_query()` instead of dropping them.
- **Offers Update Bug**: Fixed `Offers::update()` passing `$loyalty_programme_accounts` as request body instead of `$params`, and switched method to `PATCH`.
- **Query Parameter Forwarding**: Updated all resource `all()` methods to forward pagination (`limit`, `after`) and filter parameters.

### Changed
- Migrated test runner to **PHPUnit 11** / **PHPUnit 12** with updated schema in `phpunit.xml.dist`.
- Updated GitHub Actions CI workflows to test on PHP 8.4 and PHP 8.5.
