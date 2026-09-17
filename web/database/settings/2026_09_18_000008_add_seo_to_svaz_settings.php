<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('svaz.seo_title', null);
        $this->migrator->add('svaz.seo_title_en', null);
        $this->migrator->add('svaz.seo_description', null);
        $this->migrator->add('svaz.seo_description_en', null);
        $this->migrator->add('svaz.seo_image', null);
    }
};
