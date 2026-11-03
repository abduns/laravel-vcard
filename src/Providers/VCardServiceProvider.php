<?php

declare(strict_types=1);

namespace Dunn\VCard\Laravel\Providers;

use Dunn\VCard\VCard;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class VCardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('vcard', fn ($app) => new VCard());
    }

    public function boot(): void
    {
        ResponseFactory::macro('vcard', function (VCard|string $vcard, string $filename = 'contact.vcf') {
            $content = $vcard instanceof VCard ? $vcard->build() : $vcard;

            return $this->make($content, 200, [
                'Content-Type' => 'text/vcard',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        });
    }
}
