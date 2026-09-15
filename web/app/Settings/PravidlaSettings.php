<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for /pravidla — the page's 3 section headers (hero, myth/fact intro, rule
 * cards intro) plus the myth/fact repeater. Same "{field}_en" nullable sibling CZ/EN pattern as
 * KalendarSettings/SoutezeSettings. The rule cards themselves are a separate model (RuleCard,
 * see RuleCardResource) — they're repeating, image-bearing records, not page-singleton text.
 */
class PravidlaSettings extends Settings
{
    public string $hero_title;

    public ?string $hero_title_en;

    public string $hero_text;

    public ?string $hero_text_en;

    public string $myths_title;

    public ?string $myths_title_en;

    public string $myths_subtitle;

    public ?string $myths_subtitle_en;

    /**
     * Myth-vs-fact accordion items (see <x-myth-faq>). Each item: ['myth_text', 'myth_text_en',
     * 'correct_text', 'correct_text_en'] — same flat "{field}_en" sibling convention as this
     * class's own fields, repeated per row (same reasoning as KalendarSettings::$calendar_sources
     * for skipping a per-row `TranslatableTabs` toggle).
     *
     * No `@var` docblock on purpose — see KalendarSettings::$calendar_sources's own comment
     * (spatie/laravel-settings' cast reflection can't handle a nested array-of-arrays type).
     */
    public array $myths;

    public string $rule_cards_title;

    public ?string $rule_cards_title_en;

    public static function group(): string
    {
        return 'pravidla';
    }
}
