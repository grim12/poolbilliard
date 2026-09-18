<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Defaults mirror ui/src/kluby.njk 1:1 — same reasoning as
     * 2026_09_14_201349_create_homepage_settings.php's own defaults. Only the free editorial
     * text is here (heading + info-panel copy) — fixed UI chrome (button labels like
     * "Zaregistrovat nový klub") stays hardcoded in components/club-directory.blade.php pending
     * a future sitewide localization pass, not per-page settings. The info-panel's 3 icon+text
     * checklist rows also stay hardcoded, same "structural design copy, not editorial content"
     * reasoning as GeneralSettings::$cmbs_tv_url's note in components/tournaments.blade.php.
     */
    public function up(): void
    {
        $this->migrator->add('kluby.title', 'Sportovní kluby');
        $this->migrator->add('kluby.title_en', null);
        $this->migrator->add('kluby.info_tag_text', 'Pro začátečníky');
        $this->migrator->add('kluby.info_tag_text_en', null);
        $this->migrator->add('kluby.info_title', 'Chceš začít a nevíš jak?');
        $this->migrator->add('kluby.info_title_en', null);
        $this->migrator->add(
            'kluby.info_text',
            'Najdi si klub ve svém okolí a spoj se s jeho ambasadorem. Provede tě prvními kroky a poradí, jak se zapojit do tréninků i klubového života.'
        );
        $this->migrator->add('kluby.info_text_en', null);
        $this->migrator->add(
            'kluby.info_foot_text',
            'Pokud si nevíš rady, jak začít, připravili jsme pro tebe jednoduchý průvodce prvními kroky.'
        );
        $this->migrator->add('kluby.info_foot_text_en', null);
        $this->migrator->add('kluby.info_button_text', 'Jak začít');
        $this->migrator->add('kluby.info_button_text_en', null);
    }
};
