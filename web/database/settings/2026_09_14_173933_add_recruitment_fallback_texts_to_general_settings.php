<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add(
            'general.recruitment_open_fallback_text',
            '<p>Klub aktuálně přijímá nové členy — ozvěte se nám a rádi vás mezi sebe přivítáme.</p>'
        );
        $this->migrator->add(
            'general.recruitment_closed_fallback_text',
            '<p>Klub momentálně nábor nových členů neotevřel. Sledujte nás, ozveme se, jakmile se to změní.</p>'
        );
    }
};
