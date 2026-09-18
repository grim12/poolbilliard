<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('novinky.seo_title', null);
        $this->migrator->add('novinky.seo_title_en', null);
        $this->migrator->add('novinky.seo_description', null);
        $this->migrator->add('novinky.seo_description_en', null);
        $this->migrator->add('novinky.seo_image', null);
    }
};
