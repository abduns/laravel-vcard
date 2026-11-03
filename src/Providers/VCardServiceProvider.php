<?php

declare(strict_types=1);

namespace Dunn\VCard\Laravel\Providers;

use Dunn\VCard\Laravel\Support\ContentDisposition;
use Dunn\VCard\VCard;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class VCardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('vcard', fn (): VCard => new VCard());
    }

    public function boot(): void
    {
        ResponseFactory::macro('vcard', function (
            VCard|string $vcard,
            string $filename = ContentDisposition::DEFAULT_FILENAME,
        ): Response {
            // Macroable binds the closure to the ResponseFactory instance it is called on.
            /** @var ResponseFactory $this */
            return $this->make($vcard instanceof VCard ? $vcard->build() : $vcard, 200, [
                'Content-Type' => 'text/vcard',
                'Content-Disposition' => ContentDisposition::attachment($filename),
            ]);
        });
    }
}
