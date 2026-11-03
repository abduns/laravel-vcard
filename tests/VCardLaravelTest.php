<?php

declare(strict_types=1);

use Dunn\VCard\Laravel\Facades\VCard;
use Illuminate\Support\Facades\Response;

it('can generate a vcard using the facade', function (): void {
    $vcard = VCard::make()
        ->addName('Doe', 'John')
        ->build();

    expect($vcard)->toContain('BEGIN:VCARD');
    expect($vcard)->toContain('N:Doe;John;;;');
});

it('can return a vcard response using the macro', function (): void {
    $vcard = VCard::make()->addName('Doe', 'Jane');

    /** @phpstan-ignore staticMethod.notFound */
    $response = Response::vcard($vcard, 'jane.vcf');

    expect($response->headers->get('Content-Type'))->toBe('text/vcard');
    expect($response->headers->get('Content-Disposition'))->toBe('attachment; filename="jane.vcf"');
    expect($response->getContent())->toContain('N:Doe;Jane;;;');
});
