<?php

namespace App\Support;

/**
 * spatie/laravel-settings classes aren't Eloquent models, so they can't use spatie/laravel-
 * translatable — TranslatableTabs::makeForSettings() instead gives each translatable
 * `$field` a plain `$field_en` sibling property (see that method's docblock), read by nobody
 * until now. This resolves the pair the same way spatie's own getTranslation() would: the
 * English value when the locale is "en" and it's actually filled in, the Czech one otherwise
 * (including on "en" when the field hasn't been translated yet).
 */
class Locale
{
    public static function field(object $settings, string $field): ?string
    {
        if (app()->getLocale() === 'en') {
            $enValue = $settings->{"{$field}_en"} ?? null;

            if (filled($enValue)) {
                return $enValue;
            }
        }

        return $settings->{$field};
    }

    /**
     * Locale-aware equivalent of route() for the routes mirrored under the 'en.' group in
     * routes/web.php (see that group's docblock) — resolves to the current locale's own
     * route/URL instead of always the Czech one, for internal links used by templates shared
     * between both locales (back-links, section links, form actions...).
     */
    public static function route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        $name = app()->getLocale() === 'en' ? "en.{$name}" : $name;

        return route($name, $parameters, $absolute);
    }
}
