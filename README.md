# laravel-vcard

Laravel bridge for the abduns/vcard generator.

[![CI](https://github.com/abduns/laravel-vcard/actions/workflows/ci.yml/badge.svg)](https://github.com/abduns/laravel-vcard/actions/workflows/ci.yml)
[![Version](https://img.shields.io/packagist/v/abduns/laravel-vcard.svg)](https://packagist.org/packages/abduns/laravel-vcard)
[![Downloads](https://img.shields.io/packagist/dt/abduns/laravel-vcard.svg)](https://packagist.org/packages/abduns/laravel-vcard)
[![License](https://img.shields.io/packagist/l/abduns/laravel-vcard.svg)](LICENSE.md)

---

## Features

- Modern PHP support
- Lightweight and fast
- Typed API
- Laravel Facade
- Response macros for `.vcf` downloads
- Standards-oriented

---

## Installation

```bash
composer require abduns/laravel-vcard
```

---

## Quick Start

```php
use Dunn\VCard\Laravel\Facades\VCard;

return response()->vcard(
    VCard::make()
        ->addName('Doe', 'John')
        ->addEmail('john@example.com', 'work')
        ->addPhoneNumber('+62-812-3456-7890', 'cell'),
    'john_doe.vcf'
);
```

---

## Why This Package?

- Existing solutions are outdated
- Missing modern PHP features
- Poor developer experience
- No standards compliance
- Too framework-coupled

This package focuses on simplicity, interoperability, and modern developer ergonomics for generating `.vcf` files natively in your Laravel application.

---

## Usage

### Basic Usage

```php
use Dunn\VCard\Laravel\Facades\VCard;

$vcf = VCard::make()
    ->addName('Doe', 'John')
    ->addEmail('john@example.com', 'work')
    ->addPhoneNumber('+62-812-3456-7890', 'cell')
    ->addUrl('https://johndoe.com')
    ->build();
```

Output defaults to **vCard 4.0** (RFC 6350). Pass the version to `make()` to generate vCard 3.0 instead:

```php
$vcf = VCard::make('3.0')
    ->addName('Doe', 'John')
    ->build();
```

`VCard::make()` is the recommended entry point. Every call through the facade
(including `VCard::addName(...)`) resolves a fresh, empty card from the container,
so building several cards in a loop, a queue worker or an Artisan command never
mixes data from one contact into another. A chain must therefore start and end in
one expression (or be kept in a variable): separate `VCard::addX()` statements do
not add to a shared card.

### Advanced Usage

```php
public function download(User $user)
{
    $vcard = VCard::make()
        ->addName($user->last_name, $user->first_name)
        ->addEmail($user->email)
        ->addPhoneNumber($user->phone, 'cell')
        ->addUid('urn:uuid:' . $user->uuid);

    return response()->vcard($vcard, "{$user->slug}.vcf");
}
```

`response()->vcard()` (also available as `Response::vcard()`) accepts a `Dunn\VCard\VCard`
instance or a pre-built vCard string, and an optional filename that defaults to
`contact.vcf`. The response is sent as `text/vcard` with an `attachment`
`Content-Disposition` header. The filename is made safe for that header: quotes are
escaped, path separators and control characters are replaced with `_`, non-ASCII
names get an ASCII fallback plus an RFC 6266 `filename*` parameter, and an empty
name falls back to `contact.vcf`.

---

## Standards / Specifications

- RFC 6350
- RFC 6474
- RFC 6715
- RFC 8605
- RFC 9554
- RFC 9555

References:

- https://www.iana.org/assignments/vcard-elements/vcard-elements.xhtml

---

## Supported Features

| Feature | Support |
|---|---|
| Facade | ✅ |
| Response Macros | ✅ |

---

## Compatibility

| Laravel | PHP |
|---|---|
| 12.x | 8.2, 8.3, 8.4, 8.5 |
| 13.x | 8.3, 8.4, 8.5 |

Requires `abduns/vcard` ^1.1.

---

## Design Goals

- Developer experience first
- Predictable APIs
- Minimal dependencies
- Strong typing
- Native Laravel integration

---

## Architecture

- facades
- service providers
- macroable responses

---

## Performance

| Operation | Time |
|---|---|
| Generate vCard | < 1ms |

---

## Testing

```bash
composer test   # Pest
composer stan   # PHPStan + Larastan
composer lint   # php-cs-fixer (dry run); `composer fix` applies the fixes
composer ci     # all of the above
```

---

## Roadmap

- [ ] Artisan command for generating test vCards
- [ ] CardDAV response helpers
- [ ] Livewire component for vCard preview

---

## Contributing

Contributions, issues, and discussions are welcome.

---

## Security

If you discover a security issue, please email [werk@abduns.com](mailto:werk@abduns.com)
instead of opening a public issue. See [SECURITY.md](SECURITY.md) for details.

---

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for what has changed recently.

---

## License

MIT
