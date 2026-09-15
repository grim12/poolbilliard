<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for the /kluby and /herny list pages' hero (heading + info panel) — same
 * "{field}_en` nullable sibling" CZ/EN pattern as HomepageSettings/GeneralSettings, see
 * skills/web-component-guide.md's "Dvojjazyčný obsah" section. Fixed UI chrome (button/filter
 * labels reused across the site) and the info panel's 3 icon+text checklist rows stay hardcoded
 * in the Blade components — see this migration's own doc comment for why.
 */
class KlubyHernySettings extends Settings
{
    public string $kluby_title;

    public ?string $kluby_title_en;

    public string $kluby_info_tag_text;

    public ?string $kluby_info_tag_text_en;

    public string $kluby_info_title;

    public ?string $kluby_info_title_en;

    public string $kluby_info_text;

    public ?string $kluby_info_text_en;

    public string $kluby_info_foot_text;

    public ?string $kluby_info_foot_text_en;

    public string $kluby_info_button_text;

    public ?string $kluby_info_button_text_en;

    public string $herny_title;

    public ?string $herny_title_en;

    public string $herny_subtitle;

    public ?string $herny_subtitle_en;

    public string $herny_info_tag_text;

    public ?string $herny_info_tag_text_en;

    public string $herny_info_title;

    public ?string $herny_info_title_en;

    public string $herny_info_text;

    public ?string $herny_info_text_en;

    public string $herny_info_button_text;

    public ?string $herny_info_button_text_en;

    public static function group(): string
    {
        return 'kluby_herny';
    }
}
