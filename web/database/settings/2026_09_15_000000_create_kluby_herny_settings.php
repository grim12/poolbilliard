<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Defaults mirror ui/src/{kluby,herny}.njk 1:1 — same reasoning as
     * 2026_09_14_201349_create_homepage_settings.php's own defaults. Only the free editorial
     * text is here (heading/subtitle/info-panel copy) — fixed UI chrome (button labels like
     * "Zaregistrovat nový klub", filter labels like "Kraj") stays hardcoded in the Blade
     * components (club-directory, herna-list) pending a future sitewide localization pass, not
     * per-page settings. The info-panel's 3 icon+text checklist rows also stay hardcoded, same
     * "structural design copy, not editorial content" reasoning as
     * GeneralSettings::$cmbs_tv_url's note in components/tournaments.blade.php.
     */
    public function up(): void
    {
        $this->migrator->add('kluby_herny.kluby_title', 'Sportovní kluby');
        $this->migrator->add('kluby_herny.kluby_title_en', null);
        $this->migrator->add('kluby_herny.kluby_info_tag_text', 'Pro začátečníky');
        $this->migrator->add('kluby_herny.kluby_info_tag_text_en', null);
        $this->migrator->add('kluby_herny.kluby_info_title', 'Chceš začít a nevíš jak?');
        $this->migrator->add('kluby_herny.kluby_info_title_en', null);
        $this->migrator->add(
            'kluby_herny.kluby_info_text',
            'Najdi si klub ve svém okolí a spoj se s jeho ambasadorem. Provede tě prvními kroky a poradí, jak se zapojit do tréninků i klubového života.'
        );
        $this->migrator->add('kluby_herny.kluby_info_text_en', null);
        $this->migrator->add(
            'kluby_herny.kluby_info_foot_text',
            'Pokud si nevíš rady, jak začít, připravili jsme pro tebe jednoduchý průvodce prvními kroky.'
        );
        $this->migrator->add('kluby_herny.kluby_info_foot_text_en', null);
        $this->migrator->add('kluby_herny.kluby_info_button_text', 'Jak začít');
        $this->migrator->add('kluby_herny.kluby_info_button_text_en', null);

        $this->migrator->add('kluby_herny.herny_title', 'Kulečníkové herny');
        $this->migrator->add('kluby_herny.herny_title_en', null);
        $this->migrator->add(
            'kluby_herny.herny_subtitle',
            'Najděte si hernu ve svém regionu! Objevte místa, kde si můžete zahrát poolbilliard, potrénovat nebo poznat další hráče.'
        );
        $this->migrator->add('kluby_herny.herny_subtitle_en', null);
        $this->migrator->add('kluby_herny.herny_info_tag_text', 'Přidejte svou hernu');
        $this->migrator->add('kluby_herny.herny_info_tag_text_en', null);
        $this->migrator->add('kluby_herny.herny_info_title', 'Provozujete hernu?');
        $this->migrator->add('kluby_herny.herny_info_title_en', null);
        $this->migrator->add(
            'kluby_herny.herny_info_text',
            'Zařaďte ji do našeho katalogu. Vyplňte jednoduchý registrační formulář a po schválení se herna objeví v seznamu i na mapě, kde si ji najdou hráči z vašeho okolí.'
        );
        $this->migrator->add('kluby_herny.herny_info_text_en', null);
        $this->migrator->add('kluby_herny.herny_info_button_text', 'Registrovat hernu');
        $this->migrator->add('kluby_herny.herny_info_button_text_en', null);
    }
};
