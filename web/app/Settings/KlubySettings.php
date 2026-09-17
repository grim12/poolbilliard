<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for the /kluby list page's hero (heading + info panel) — same "{field}_en"
 * nullable sibling CZ/EN pattern as HomepageSettings/GeneralSettings, see
 * skills/web-component-guide.md's "Dvojjazyčný obsah" section. Kept separate from HernySettings
 * even though the shape is nearly identical — /kluby and /herny are two different pages, each
 * with its own content, not one combined settings screen. Fixed UI chrome (button labels reused
 * across the site) and the info panel's 3 icon+text checklist rows stay hardcoded in
 * components/club-directory.blade.php — see this settings class's migration for why.
 */
class KlubySettings extends Settings
{
    public string $title;

    public ?string $title_en;

    public string $info_tag_text;

    public ?string $info_tag_text_en;

    public string $info_title;

    public ?string $info_title_en;

    public string $info_text;

    public ?string $info_text_en;

    public string $info_foot_text;

    public ?string $info_foot_text_en;

    public string $info_button_text;

    public ?string $info_button_text_en;

    /**
     * Nepovinné přepsání výchozích SEO hodnot (viz App\Support\Seo) — necháš-li prázdné, použije
     * se automaticky vypočtený titulek/popis (viz kluby.blade.php).
     */
    public ?string $seo_title;

    public ?string $seo_title_en;

    public ?string $seo_description;

    public ?string $seo_description_en;

    public ?string $seo_image;

    public static function group(): string
    {
        return 'kluby';
    }
}
