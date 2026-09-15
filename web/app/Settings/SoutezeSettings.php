<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for the /souteze page's <x-page-hero> (title/subtitle/text + up to 4 stat
 * tiles) — same "{field}_en" nullable sibling CZ/EN pattern as KlubySettings/HernySettings/
 * KalendarSettings, see skills/web-component-guide.md's "Dvojjazyčný obsah" section. The
 * numbered content sections and leaderboards below the hero are separate models
 * (CompetitionSection, Leaderboard), not settings — they're repeating records, not
 * page-singleton text.
 */
class SoutezeSettings extends Settings
{
    public string $title;

    public ?string $title_en;

    public string $subtitle;

    public ?string $subtitle_en;

    public string $text;

    public ?string $text_en;

    /**
     * Up to 4 stat tiles (2×2 grid, see <x-page-hero>). Each item: ['value', 'label',
     * 'label_en'] — `value` isn't translated (it's a bare number as a string, e.g. "6"), only
     * `label` has a CZ/EN pair, same flat sibling convention as this class's own fields.
     *
     * No `@var` docblock on purpose — see KalendarSettings::$calendar_sources's own comment
     * (spatie/laravel-settings' cast reflection can't handle a nested array-of-arrays type).
     */
    public array $stats;

    public static function group(): string
    {
        return 'souteze';
    }
}
