<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('partneri.seo_title', null);
        $this->migrator->add('partneri.seo_title_en', null);
        $this->migrator->add('partneri.seo_description', null);
        $this->migrator->add('partneri.seo_description_en', null);
        $this->migrator->add('partneri.seo_image', null);
    }
};
