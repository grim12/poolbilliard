<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for the /kalendar page's header (title + lead subtitle) — same "{field}_en"
 * nullable sibling CZ/EN pattern as KlubySettings/HernySettings, see
 * skills/web-component-guide.md's "Dvojjazyčný obsah" section. No info panel here (unlike
 * Kluby/Herny) — Kalendář's header is just <x-news-header>'s title/subtitle, the rest of the
 * page (filter pills, cards, mini-calendar, sidebar) isn't editorial content.
 */
class KalendarSettings extends Settings
{
    public string $title;

    public ?string $title_en;

    public string $subtitle;

    public ?string $subtitle_en;

    public static function group(): string
    {
        return 'kalendar';
    }
}
