# Changelog

All notable changes to `abduns/laravel-vcard` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Fixed

- The `VCard` facade no longer caches the resolved `Dunn\VCard\VCard`. Every static
  call now resolves a fresh instance, so static chains such as
  `VCard::addName(...)->addEmail(...)->build()` no longer leak FN, EMAIL, TEL and other
  properties from one card into the next (in loops, queue workers and console commands).
  **Behaviour change:** separate statements such as `VCard::addName(...);` followed by
  `VCard::build();` no longer share one card; build each card in a single chain or keep
  the instance from `VCard::make()` in a variable.
- `response()->vcard()` now builds a valid `Content-Disposition` header:
  - quotes no longer break the quoted filename (they are escaped);
  - `/`, `\` and control characters (including CR/LF) are replaced with `_`;
  - surrounding whitespace is trimmed;
  - non-ASCII filenames get a transliterated ASCII fallback plus an RFC 6266
    `filename*` parameter carrying the original name;
  - `%` is dropped from the ASCII fallback (some clients percent-decode it), so names
    containing `%` also get a `filename*` parameter with the original name;
  - the ASCII fallback becomes `contact.vcf` when the part before the extension has no
    letter or digit (for example `_.vcf` or `.vcf`); the original name is still sent
    in `filename*`;
  - empty or whitespace-only filenames, and filenames that are not valid UTF-8, fall
    back to `contact.vcf`.

  **Behaviour change:** the header differs from before for every filename covered by
  the rules above. Other filenames, such as `jane.vcf` or `Jane Doe.vcf`, produce the
  same header as before.
- The facade `@method` docblock now matches `abduns/vcard` 1.1: `make()` defaults to
  `'4.0'`, `addPhoto()`, `addLogo()`, `addSound()` and `addKey()` take `$uriOrBase64`
  (named arguments that followed the old docblock failed at runtime), and the documented
  type and media-type defaults are `'cell'`, `'work'`, `'image/jpeg'` and `'audio/ogg'`.

### Changed

- Require `abduns/vcard` `^1.1` (was `^1.0 || dev-main`). 1.0.x defaults to vCard 3.0,
  uses upper-case TYPE values and does not fold long lines.
- Declare the `illuminate/routing` and `illuminate/http` dependencies used by the
  response macro.
- Removed the `../vcard` path repository from `composer.json`, so the package installs
  standalone.
- Package metadata: real author contact, homepage and support links, more keywords.
- Development-only files (tests, CI, tool configs) are no longer included in the dist
  archive.
- CI now covers PHP 8.2–8.5 with Laravel 12 and 13 at lowest and stable dependencies,
  and runs `composer validate`, PHPStan (with Larastan) and php-cs-fixer.
