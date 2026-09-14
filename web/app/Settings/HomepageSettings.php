<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomepageSettings extends Settings
{
    public string $featured_articles_button_text;

    public string $notices_title;

    public string $notices_subtitle;

    public string $notices_button_text;

    /**
     * Nullable — sekce se na homepage vůbec nevypíše, pokud tu není vybraný žádný Banner (nebo
     * byl vybraný záznam mezitím smazaný). Oba sloty renderují přes stejnou komponentu, Banner
     * entita je pro obě varianty (event promo i cta) sjednocená.
     */
    public ?int $banner_1_id;

    public string $tournaments_title;

    public string $tournaments_button_text;

    public ?int $banner_2_id;

    public string $leaderboards_title;

    public string $leaderboards_subtitle;

    public string $leaderboards_button_text;

    /**
     * Uspořádané pole LinkTile ID — pořadí ve výběru = pořadí zobrazení. Prázdné pole = sekce
     * se nevypíše. Which page shows which tiles is deliberately not a property of LinkTile
     * itself, viz skills/web-component-guide.md.
     *
     * @var array<int, int>
     */
    public array $link_tile_ids;

    public string $partners_title;

    public string $partners_button_text;

    public static function group(): string
    {
        return 'homepage';
    }
}
