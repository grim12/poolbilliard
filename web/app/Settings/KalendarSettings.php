<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for the /kalendar page's header (title + lead subtitle) — same "{field}_en"
 * nullable sibling CZ/EN pattern as KlubySettings/HernySettings, see
 * skills/web-component-guide.md's "Dvojjazyčný obsah" section. No info panel here (unlike
 * Kluby/Herny) — Kalendář's header is just <x-news-header>'s title/subtitle, the rest of the
 * page (filter pills, cards, mini-calendar) isn't editorial content — except the two sidebar
 * cards below, which are.
 */
class KalendarSettings extends Settings
{
    public string $title;

    public ?string $title_en;

    public string $subtitle;

    public ?string $subtitle_en;

    /**
     * "Chceš si zahrát?" recurring-tournaments sidebar card (tag/title/text) —
     * <x-recurring-tournaments> itself keeps hardcoded UI defaults for reuse elsewhere, but the
     * copy actually shown on /kalendar is admin-editable, same reasoning as Kluby/Herny's info
     * panel text.
     */
    public string $recurring_tag_text;

    public ?string $recurring_tag_text_en;

    public string $recurring_title;

    public ?string $recurring_title_en;

    public string $recurring_text;

    public ?string $recurring_text_en;

    /**
     * "Zdrojové kalendáře" sidebar list — plain array of ['title', 'subtitle', 'url'], not
     * translatable per-item (same call as Club::$members Repeater: these are external
     * organization names — ČMBS/EPBF/EEBC/Matchroom — that wouldn't change in an English
     * version anyway, and per-row CZ/EN tabs inside a Repeater have no established pattern yet
     * in this project). Admin-managed repeater, not a separate model/table — this list only
     * exists on this one page, no querying/reuse elsewhere (unlike e.g. LinkTile). Each item:
     * ['title' => string, 'subtitle' => string, 'url' => string].
     *
     * No `@var` docblock on purpose — spatie/laravel-settings reflects it to build a cast, and
     * its `ArraySettingsCast` can't handle a nested array-of-arrays value type (crashes trying
     * to build a cast for the inner array). A bare `array` property with no docblock type skips
     * cast resolution entirely (see Spatie\LaravelSettings\Support\PropertyReflector), which is
     * exactly right here — no per-item casting needed, just raw JSON in, raw array out.
     */
    public array $calendar_sources;

    public static function group(): string
    {
        return 'kalendar';
    }
}
