<?php

declare(strict_types=1);

use Dunn\VCard\Laravel\Facades\VCard;
use Dunn\VCard\Laravel\Providers\VCardServiceProvider;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

/**
 * @return array{providers?: list<string>, aliases?: array<string, string>}
 */
function laravelDiscoveryConfig(): array
{
    $composer = json_decode((string) file_get_contents(__DIR__ . '/../composer.json'), true, 512, JSON_THROW_ON_ERROR);

    expect($composer)->toBeArray()->toHaveKey('extra.laravel');

    /** @var array{extra: array{laravel: array{providers?: list<string>, aliases?: array<string, string>}}} $composer */
    return $composer['extra']['laravel'];
}

it('registers the service provider for package auto-discovery', function (): void {
    $providers = laravelDiscoveryConfig()['providers'] ?? [];

    expect($providers)->toBe([VCardServiceProvider::class])
        ->and(is_subclass_of($providers[0], ServiceProvider::class))->toBeTrue();
});

it('registers the VCard facade alias for package auto-discovery', function (): void {
    $aliases = laravelDiscoveryConfig()['aliases'] ?? [];

    expect($aliases)->toBe(['VCard' => VCard::class])
        ->and(is_subclass_of($aliases['VCard'], Facade::class))->toBeTrue();
});
