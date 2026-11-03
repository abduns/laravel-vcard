<?php

declare(strict_types=1);

use Dunn\VCard\Laravel\Facades\VCard;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

/**
 * Response::vcard() is registered at runtime through ResponseFactory::macro(),
 * which static analysis cannot see.
 */
function vcardResponse(mixed ...$arguments): HttpResponse
{
    /** @phpstan-ignore staticMethod.notFound */
    return Response::vcard(...$arguments);
}

function vcardResponseViaHelper(mixed ...$arguments): HttpResponse
{
    /** @phpstan-ignore method.notFound */
    return response()->vcard(...$arguments);
}

it('accepts a pre-built vCard string', function (): void {
    $vcf = VCard::make()->addName('Doe', 'Jane')->build();

    $response = vcardResponse($vcf, 'jane.vcf');

    expect($response->getContent())->toBe($vcf)
        ->and($response->getStatusCode())->toBe(200)
        ->and($response->headers->get('Content-Type'))->toBe('text/vcard');
});

it('uses contact.vcf as the default filename', function (): void {
    $response = vcardResponse(VCard::make()->addName('Doe', 'Jane'));

    expect($response->headers->get('Content-Disposition'))->toBe('attachment; filename="contact.vcf"');
});

it('is available through the response() helper', function (): void {
    $response = vcardResponseViaHelper(VCard::make()->addName('Doe', 'Jane'), 'jane.vcf');

    expect($response->headers->get('Content-Disposition'))->toBe('attachment; filename="jane.vcf"');
});

it('builds a safe Content-Disposition header', function (string $filename, string $expected): void {
    $response = vcardResponse(VCard::make()->addName('Doe', 'Jane'), $filename);

    expect($response->headers->get('Content-Disposition'))->toBe($expected);
})->with([
    'plain name' => ['jane.vcf', 'attachment; filename="jane.vcf"'],
    'spaces' => ['Jane Doe.vcf', 'attachment; filename="Jane Doe.vcf"'],
    'double quote' => ['a"b.vcf', 'attachment; filename="a\"b.vcf"'],
    'backslash' => ['a\b.vcf', 'attachment; filename="a_b.vcf"'],
    'slash' => ['../etc/passwd.vcf', 'attachment; filename=".._etc_passwd.vcf"'],
    'CR/LF' => ["x\r\nSet-Cookie: a=b.vcf", 'attachment; filename="x__Set-Cookie: a=b.vcf"'],
    'non-ASCII' => [
        'José Müller.vcf',
        "attachment; filename=\"Jose Muller.vcf\"; filename*=utf-8''Jos%C3%A9%20M%C3%BCller.vcf",
    ],
    'percent sign' => ['100%.vcf', "attachment; filename=\"100.vcf\"; filename*=utf-8''100%25.vcf"],
    'non-transliterable' => ['📇.vcf', "attachment; filename=\"contact.vcf\"; filename*=utf-8''%F0%9F%93%87.vcf"],
    'no letter or digit in the name' => ['_.vcf', "attachment; filename=\"contact.vcf\"; filename*=utf-8''_.vcf"],
    'extension only' => ['.vcf', "attachment; filename=\"contact.vcf\"; filename*=utf-8''.vcf"],
    'surrounding whitespace' => [' jane.vcf ', 'attachment; filename="jane.vcf"'],
    'invalid UTF-8' => ["\xFF\xFE.vcf", 'attachment; filename="contact.vcf"'],
    'empty string' => ['', 'attachment; filename="contact.vcf"'],
    'whitespace only' => ['   ', 'attachment; filename="contact.vcf"'],
]);

it('emits a header that survives being sent', function (): void {
    $response = vcardResponse(VCard::make()->addName('Doe', 'Jane'), "evil\r\nX-Injected: 1");

    expect($response->headers->has('X-Injected'))->toBeFalse()
        ->and((string) $response->headers->get('Content-Disposition'))->not->toMatch('/[\r\n]/');
});
