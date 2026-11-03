<?php

declare(strict_types=1);

use Dunn\VCard\Laravel\Facades\VCard;
use Dunn\VCard\VCard as CoreVCard;

it('resolves a new VCard instance from the container on every call', function (): void {
    expect(app('vcard') instanceof CoreVCard)->toBeTrue()
        ->and(app('vcard') === app('vcard'))->toBeFalse();
});

it('does not cache the facade root between calls', function (): void {
    expect(VCard::getFacadeRoot())->toBeInstanceOf(CoreVCard::class)
        ->and(VCard::getFacadeRoot())->not->toBe(VCard::getFacadeRoot());
});

it('does not leak data between consecutive cards built through static facade calls', function (): void {
    $first = VCard::addName('Doe', 'John')
        ->addEmail('john@example.test')
        ->addPhoneNumber('+1-555-0100')
        ->build();

    $second = VCard::addName('Smith', 'Alice')->build();

    expect($first)
        ->toContain('FN:John Doe')
        ->toContain('EMAIL;TYPE=work:john@example.test');

    expect($second)->toContain('N:Smith;Alice;;;', 'FN:Alice Smith');
    expect($second)->not->toContain('John')
        ->and($second)->not->toContain('EMAIL')
        ->and($second)->not->toContain('TEL');
});

it('does not leak data between consecutive cards built with make()', function (): void {
    $first = VCard::make()->addName('Doe', 'John')->addEmail('john@example.test')->build();
    $second = VCard::make()->addName('Smith', 'Alice')->build();

    expect($first)->toContain('EMAIL;TYPE=work:john@example.test')
        ->and($second)->toContain('FN:Alice Smith')
        ->and($second)->not->toContain('EMAIL')
        ->and($second)->not->toContain('John');
});

it('starts every separate static call from an empty card', function (): void {
    VCard::addName('Doe', 'John');

    expect(fn () => VCard::build())->toThrow(InvalidArgumentException::class);
});

it('can still be swapped for a test double', function (): void {
    $fake = CoreVCard::make()->addFormattedName('Swapped');

    VCard::swap($fake);

    expect(VCard::getFacadeRoot())->toBe($fake)
        ->and(VCard::build())->toContain('FN:Swapped');
});

it('outputs vCard 4.0 with lower-case TYPE parameters by default', function (): void {
    $vcf = VCard::make()
        ->addName('Doe', 'John')
        ->addEmail('john@example.test')
        ->addPhoneNumber('+1-555-0100')
        ->build();

    expect($vcf)
        ->toContain("BEGIN:VCARD\r\nVERSION:4.0\r\n")
        ->toContain('EMAIL;TYPE=work:john@example.test')
        ->toContain('TEL;TYPE=cell:+1-555-0100');
});

it('outputs vCard 3.0 when requested through make()', function (): void {
    expect(VCard::make('3.0')->addName('Doe', 'John')->build())
        ->toContain("BEGIN:VCARD\r\nVERSION:3.0\r\n");
});

/**
 * Parse the facade's "@method static <return> <name>(<params>)" tags.
 *
 * @return array<string, array{return: string, params: list<array{type: string, variadic: bool, name: string, default: string|null}>}>
 */
function documentedFacadeMethods(): array
{
    $doc = (string) (new ReflectionClass(VCard::class))->getDocComment();

    preg_match_all('/@method\s+static\s+(\S+)\s+(\w+)\((.*)\)\s*$/m', $doc, $methods, PREG_SET_ORDER);

    $documented = [];

    foreach ($methods as [, $return, $name, $paramList]) {
        preg_match_all(
            '/(?:^|,)\s*([^$]*?)\s*(\.\.\.)?\$(\w+)(?:\s*=\s*([^,]+?))?\s*(?=,|$)/',
            $paramList,
            $params,
            PREG_SET_ORDER,
        );

        $documented[$name] = [
            'return' => ltrim($return, '\\'),
            'params' => array_map(static fn (array $p): array => [
                'type' => (string) preg_replace('/<.*>$/', '', $p[1]),
                'variadic' => $p[2] === '...',
                'name' => $p[3],
                'default' => $p[4] ?? null,
            ], $params),
        ];
    }

    return $documented;
}

it('documents every public method of the core VCard on the facade', function (): void {
    $core = array_values(array_filter(
        (new ReflectionClass(CoreVCard::class))->getMethods(ReflectionMethod::IS_PUBLIC),
        static fn (ReflectionMethod $m): bool => ! $m->isConstructor(),
    ));

    $documented = documentedFacadeMethods();

    expect(array_keys($documented))->toEqualCanonicalizing(array_map(
        static fn (ReflectionMethod $m): string => $m->getName(),
        $core,
    ));

    foreach ($core as $method) {
        $doc = $documented[$method->getName()];

        $returnType = $method->getReturnType();
        $expectedReturn = $returnType instanceof ReflectionNamedType && $returnType->getName() === 'self'
            ? CoreVCard::class
            : (string) $returnType;

        expect($doc['return'])->toBe($expectedReturn, "Return type of {$method->getName()}()");

        $expectedParams = array_map(static fn (ReflectionParameter $p): array => [
            'type' => (string) $p->getType(),
            'variadic' => $p->isVariadic(),
            'name' => $p->getName(),
            'default' => $p->isDefaultValueAvailable()
                ? ($p->getDefaultValue() === [] ? '[]' : var_export($p->getDefaultValue(), true))
                : null,
        ], $method->getParameters());

        expect($doc['params'])->toBe($expectedParams, "Parameters of {$method->getName()}()");
    }
});
