<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for /sportovni-svaz that isn't one of the repeating models (CommitteeMember,
 * DocumentCategory/Document): the <x-page-hero>, the "Českomoravský billiardový svaz" intro
 * column (text + external links + task links), the "Výkonný výbor" column's intro (email/IBAN
 * — see below), and the section titles for the documents archive and the notices widget. Same
 * "{field}_en" nullable sibling CZ/EN pattern as SoutezeSettings/PravidlaSettings.
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
     * The surrounding sentences ("Pro zprávy určené celému Výkonnému výboru...", "Číslo účtu:
     * ...") stay hardcoded in the Blade view — structural copy, same reasoning as
     * GeneralSettings::$cmbs_tv_url's hardcoded note. Only the actual email/IBAN are editable,
     * and neither is translated (contact data, not editorial text).
     */
    public string $committee_email;

    public string $committee_iban;

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
