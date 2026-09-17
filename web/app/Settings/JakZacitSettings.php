<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for /jak-zacit that isn't one of the 3 audience paths (JakZacitSection): the
 * <x-page-hero> title/text, the "Kde začít" quick-select feature cards, and the closing
 * "Připraveni začít?" + match-form section headers. Same "{field}_en" nullable sibling CZ/EN
 * pattern as SoutezeSettings/PravidlaSettings.
 */
class JakZacitSettings extends Settings
{
    public string $hero_title;

    public ?string $hero_title_en;

    public string $hero_text;

    public ?string $hero_text_en;

    /**
     * The 4 "Kde začít" quick-select cards above the audience sections. Each item: ['icon',
     * 'title', 'title_en', 'text', 'text_en', 'link_text', 'link_text_en', 'link_url'] —
     * `link_url` isn't translated (a same-page `#anchor`, shared for both languages). Two cards
     * intentionally point at the same JakZacitSection anchor (ui/'s mock links both "Už hraji s
     * kamarády" and "Chci se zlepšit" to #rekreacni-hrac) — not a 1:1 relationship with
     * sections, so this stays its own repeater instead of being derived from JakZacitSection.
     *
     * No `@var` docblock on purpose — see KalendarSettings::$calendar_sources's own comment.
     */
    public array $feature_cards;

    public string $cta_title;

    public ?string $cta_title_en;

    public string $cta_text;

    public ?string $cta_text_en;

    public string $match_form_eyebrow;

    public ?string $match_form_eyebrow_en;

    public string $match_form_title;

    public ?string $match_form_title_en;

    public string $match_form_text;

    public ?string $match_form_text_en;

    /**
     * Nepovinné přepsání výchozích SEO hodnot (viz App\Support\Seo) — necháš-li prázdné, použije
     * se automaticky vypočtený titulek/popis (viz jak-zacit.blade.php).
     */
    public ?string $seo_title;

    public ?string $seo_title_en;

    public ?string $seo_description;

    public ?string $seo_description_en;

    public ?string $seo_image;

    public static function group(): string
    {
        return 'jak_zacit';
    }
}
