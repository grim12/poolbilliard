<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Editorial text for /pravidla — just the page's 3 section headers (hero, myth/fact intro,
 * rule cards intro). Same "{field}_en" nullable sibling CZ/EN pattern as KalendarSettings/
 * SoutezeSettings. Both the myth/fact items (Myth) and the rule cards (RuleCard) are separate
 * models, not settings fields — they're repeating records with their own admin CRUD, not
 * page-singleton text. Myth started as a Repeater here, then got promoted to its own entity
 * once it became clear myths might get reused/grouped elsewhere later (see MythResource).
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

    public string $rule_cards_title;

    public ?string $rule_cards_title_en;

    public static function group(): string
    {
        return 'pravidla';
    }
}
