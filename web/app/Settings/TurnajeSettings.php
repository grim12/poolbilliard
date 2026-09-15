<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for the /turnaje list page's header — same "{field}_en" nullable sibling CZ/EN
 * pattern as KlubySettings/HernySettings, see skills/web-component-guide.md's "Dvojjazyčný
 * obsah" section. Separate from HomepageSettings::$tournaments_title — that one is the
 * homepage's own "Nejbližší turnaje" section title, a different page with its own copy.
 */
class TurnajeSettings extends Settings
{
    public string $title;

    public ?string $title_en;

    public static function group(): string
    {
        return 'turnaje';
    }
}
