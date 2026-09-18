<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Defaults mirror ui/src/index.njk 1:1, including which Banner/LinkTile records are picked —
     * banner_1_id=1 ("Federal Cup 2026") / banner_2_id=2 ("Hraješ s kamarády...") and
     * link_tile_ids=[1,2,3,4] ("Začni hrát"/"Pravidla"/"Systémy soutěží"/"O svazu") match
     * BannerSeeder's/LinkTileSeeder's insertion order on a fresh `migrate:fresh --seed`, so the
     * homepage looks right out of the box without extra manual admin setup.
     */
    public function up(): void
    {
        $this->migrator->add('homepage.featured_articles_button_text', 'Další novinky');
        $this->migrator->add('homepage.featured_articles_button_text_en', null);
        $this->migrator->add('homepage.notices_title', 'Zprávy výkonného výboru');
        $this->migrator->add('homepage.notices_title_en', null);
        $this->migrator->add('homepage.notices_subtitle', 'Oficiální rozhodnutí a důležité informace');
        $this->migrator->add('homepage.notices_subtitle_en', null);
        $this->migrator->add('homepage.notices_button_text', 'Všechny zprávy VV');
        $this->migrator->add('homepage.notices_button_text_en', null);
        $this->migrator->add('homepage.banner_1_id', 1);
        $this->migrator->add('homepage.tournaments_title', 'Nejbližší turnaje');
        $this->migrator->add('homepage.tournaments_title_en', null);
        $this->migrator->add('homepage.tournaments_button_text', 'Kompletní kalendář');
        $this->migrator->add('homepage.tournaments_button_text_en', null);
        $this->migrator->add('homepage.banner_2_id', 2);
        $this->migrator->add('homepage.leaderboards_title', 'Žebříčky');
        $this->migrator->add('homepage.leaderboards_title_en', null);
        $this->migrator->add('homepage.leaderboards_subtitle', 'Nejlepší hráči aktuální sezóny, různé série, od juniorů po veterány');
        $this->migrator->add('homepage.leaderboards_subtitle_en', null);
        $this->migrator->add('homepage.leaderboards_button_text', 'Systémy soutěží');
        $this->migrator->add('homepage.leaderboards_button_text_en', null);
        $this->migrator->add('homepage.link_tile_ids', [1, 2, 3, 4]);
        $this->migrator->add('homepage.partners_title', 'Partneři českého poolu');
        $this->migrator->add('homepage.partners_title_en', null);
        $this->migrator->add('homepage.partners_button_text', 'Partneři a sponzoři');
        $this->migrator->add('homepage.partners_button_text_en', null);
    }
};
