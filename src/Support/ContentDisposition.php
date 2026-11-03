<?php

declare(strict_types=1);

namespace Dunn\VCard\Laravel\Support;

use Illuminate\Support\Str;

/**
 * Builds a safe "Content-Disposition: attachment" header value for vCard downloads.
 *
 * @internal
 */
final class ContentDisposition
{
    public const DEFAULT_FILENAME = 'contact.vcf';

    /**
     * Path separators and control characters (including CR/LF) are replaced with "_",
     * quotes and backslashes are escaped inside the quoted filename, and non-ASCII
     * names get an ASCII fallback plus an RFC 6266 / RFC 5987 filename* parameter.
     */
    public static function attachment(string $filename): string
    {
        $filename = self::sanitize($filename);
        $fallback = self::asciiFallback($filename);

        $header = 'attachment; filename="' . addcslashes($fallback, '"\\') . '"';

        if ($fallback !== $filename) {
            $header .= "; filename*=utf-8''" . rawurlencode($filename);
        }

        return $header;
    }

    private static function sanitize(string $filename): string
    {
        $sanitized = preg_replace('/[\x00-\x1F\x7F\/\\\\]/u', '_', $filename);

        // preg_replace() returns null for invalid UTF-8 input.
        $sanitized = trim($sanitized ?? '');

        return $sanitized === '' ? self::DEFAULT_FILENAME : $sanitized;
    }

    private static function asciiFallback(string $filename): string
    {
        // Keep printable ASCII only; "%" is dropped because some clients percent-decode it.
        $fallback = trim((string) preg_replace('/[^\x20-\x7E]|%/', '', Str::ascii($filename)));

        return preg_match('/[A-Za-z0-9]/', pathinfo($fallback, PATHINFO_FILENAME)) === 1
            ? $fallback
            : self::DEFAULT_FILENAME;
    }
}
