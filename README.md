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

| Platform | Supported |
|---|---|
| PHP 8.2+ | ✅ |
| Laravel 12.0+ | ✅ |

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
composer test
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

If you discover security issues, please report them responsibly.

---

## License

MIT
