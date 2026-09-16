<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for /sportovni-svaz that isn't one of the repeating models (CommitteeMember,
 * DocumentCategory/Document): the <x-page-hero>, the "Českomoravský billiardový svaz" intro
 * column (text + external links + task links), the "Výkonný výbor" column's intro text, and the
 * section titles for the documents archive and the notices widget. Same "{field}_en" nullable
 * sibling CZ/EN pattern as SoutezeSettings/PravidlaSettings.
 */
class SvazSettings extends Settings
{
    public string $hero_title;

    public ?string $hero_title_en;

    public string $hero_text;

    public ?string $hero_text_en;

    public string $info_title;

    public ?string $info_title_en;

    public string $info_text;

    public ?string $info_text_en;

    public string $cmbs_website_url;

    public string $cmbs_bylaws_url;

    public string $tasks_title;

    public ?string $tasks_title_en;

    /**
     * The "Potřebuji vyřídit …" link list. Each item: ['text', 'text_en', 'url'] — `url` isn't
     * translated (an external ČMBS system link, shared for both languages), same flat sibling
     * convention as KalendarSettings::$calendar_sources.
     *
     * No `@var` docblock on purpose — see KalendarSettings::$calendar_sources's own comment.
     */
    public array $tasks;

    public string $committee_title;

    public ?string $committee_title_en;

    /**
     * Freeform rich text below the committee title — ui/'s mock hardcodes two separate
     * sentences (a mailto link paragraph + a "Číslo účtu: ..." paragraph), but there's nothing
     * structural about that split, so it's just one RichEditor field. An admin editing the
     * account number types it as its own paragraph, same as ui/'s markup.
     */
    public string $committee_text;

    public ?string $committee_text_en;

    public string $documents_title;

    public ?string $documents_title_en;

    public string $notices_title;

    public ?string $notices_title_en;

    public string $notices_subtitle;

    public ?string $notices_subtitle_en;

    public static function group(): string
    {
        return 'svaz';
    }
}
