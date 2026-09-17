<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('kluby.seo_title', null);
        $this->migrator->add('kluby.seo_title_en', null);
        $this->migrator->add('kluby.seo_description', null);
        $this->migrator->add('kluby.seo_description_en', null);
        $this->migrator->add('kluby.seo_image', null);
    }
};
