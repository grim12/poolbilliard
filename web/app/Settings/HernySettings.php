<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for the /herny list page's hero (heading + info panel) — see KlubySettings'
 * doc comment (its sibling page, kept as a separate settings class/group on purpose).
 */
class HernySettings extends Settings
{
    public string $title;

    public ?string $title_en;

    public string $subtitle;

    public ?string $subtitle_en;

    public string $info_tag_text;

    public ?string $info_tag_text_en;

    public string $info_title;

    public ?string $info_title_en;

    public string $info_text;

    public ?string $info_text_en;

    public string $info_button_text;

    public ?string $info_button_text_en;

    /**
     * Nepovinné přepsání výchozích SEO hodnot (viz App\Support\Seo) — necháš-li prázdné, použije
     * se automaticky vypočtený titulek/popis (viz herny.blade.php).
     */
    public ?string $seo_title;

    public ?string $seo_title_en;

    public ?string $seo_description;

    public ?string $seo_description_en;

    public ?string $seo_image;

    public static function group(): string
    {
        return 'herny';
    }
}
