<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Defaults mirror ui/src/kalendar.njk's hardcoded title/subtitle 1:1 — same reasoning as
     * create_kluby_settings.php's own defaults.
     */
    public function up(): void
    {
        $this->migrator->add('kalendar.title', 'Kalendář');
        $this->migrator->add('kalendar.title_en', null);
        $this->migrator->add(
            'kalendar.subtitle',
            'Přehled turnajů a akcí Českého poolbilliardu — svazové soutěže, kluby i mezinárodní turnaje na jednom místě.'
        );
        $this->migrator->add('kalendar.subtitle_en', null);

        $this->migrator->add('kalendar.recurring_tag_text', 'Amatérské turnaje');
        $this->migrator->add('kalendar.recurring_tag_text_en', null);
        $this->migrator->add('kalendar.recurring_title', 'Chceš si zahrát?');
        $this->migrator->add('kalendar.recurring_title_en', null);
        $this->migrator->add(
            'kalendar.recurring_text',
            'Vyzkoušej si sportovní atmosféru a šanci uhrát výsledek, i jako začátečník.'
        );
        $this->migrator->add('kalendar.recurring_text_en', null);

        $this->migrator->add('kalendar.calendar_sources', [
            ['title' => 'ČMBS kalendář', 'subtitle' => 'Svazové soutěže a akce', 'url' => '#'],
            ['title' => 'EPBF kalendář', 'subtitle' => 'European Pocket Billiard Federation', 'url' => '#'],
            ['title' => 'EEBC kalendář', 'subtitle' => 'East European Billiard Council', 'url' => '#'],
            ['title' => 'Matchroom kalendář', 'subtitle' => 'Matchroom Pool', 'url' => '#'],
        ]);
    }
};
