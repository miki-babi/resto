<?php

namespace App\Helpers;

class Language
{
    /**
     * Get the list of supported locales.
     *
     * @return array
     */
    public static function supported(): array
    {
        return ['en', 'am'];
    }

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public static function current(): string
    {
        return app()->getLocale();
    }

    /**
     * Check if the current locale is Amharic.
     *
     * @return bool
     */
    public static function isAmharic(): bool
    {
        return self::current() === 'am';
    }

    /**
     * Check if the current locale is English.
     *
     * @return bool
     */
    public static function isEnglish(): bool
    {
        return self::current() === 'en';
    }
}
