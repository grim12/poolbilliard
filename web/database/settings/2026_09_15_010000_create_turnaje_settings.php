<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Default mirrors the hardcoded title="Nejbližší turnaje" that turnaje.blade.php used to
     * pass to <x-tournaments> — same reasoning as create_kluby_settings.php's own defaults.
     */
    public function up(): void
    {
        $this->migrator->add('turnaje.title', 'Nejbližší turnaje');
        $this->migrator->add('turnaje.title_en', null);
    }
};
