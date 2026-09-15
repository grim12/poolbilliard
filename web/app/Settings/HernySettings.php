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

    public static function group(): string
    {
        return 'herny';
    }
}
