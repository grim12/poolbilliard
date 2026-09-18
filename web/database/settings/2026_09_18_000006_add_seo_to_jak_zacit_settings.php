<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('jak_zacit.seo_title', null);
        $this->migrator->add('jak_zacit.seo_title_en', null);
        $this->migrator->add('jak_zacit.seo_description', null);
        $this->migrator->add('jak_zacit.seo_description_en', null);
        $this->migrator->add('jak_zacit.seo_image', null);
    }
};
