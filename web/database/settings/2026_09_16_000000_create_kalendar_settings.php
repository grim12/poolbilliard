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
    }
};
