<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('pravidla.seo_title', null);
        $this->migrator->add('pravidla.seo_title_en', null);
        $this->migrator->add('pravidla.seo_description', null);
        $this->migrator->add('pravidla.seo_description_en', null);
        $this->migrator->add('pravidla.seo_image', null);
    }
};
