<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Defaults mirror ui/src/herny.njk 1:1 — same reasoning as
     * database/settings/2026_09_15_000000_create_kluby_settings.php's own defaults (its sibling
     * page, kept as a separate settings class/group even though the shape is nearly identical —
     * /kluby and /herny are two different pages, not one).
     */
    public function up(): void
    {
        $this->migrator->add('herny.title', 'Kulečníkové herny');
        $this->migrator->add('herny.title_en', null);
        $this->migrator->add(
            'herny.subtitle',
            'Najděte si hernu ve svém regionu! Objevte místa, kde si můžete zahrát poolbilliard, potrénovat nebo poznat další hráče.'
        );
        $this->migrator->add('herny.subtitle_en', null);
        $this->migrator->add('herny.info_tag_text', 'Přidejte svou hernu');
        $this->migrator->add('herny.info_tag_text_en', null);
        $this->migrator->add('herny.info_title', 'Provozujete hernu?');
        $this->migrator->add('herny.info_title_en', null);
        $this->migrator->add(
            'herny.info_text',
            'Zařaďte ji do našeho katalogu. Vyplňte jednoduchý registrační formulář a po schválení se herna objeví v seznamu i na mapě, kde si ji najdou hráči z vašeho okolí.'
        );
        $this->migrator->add('herny.info_text_en', null);
        $this->migrator->add('herny.info_button_text', 'Registrovat hernu');
        $this->migrator->add('herny.info_button_text_en', null);
    }
};
