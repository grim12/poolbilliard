<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('kalendar.seo_title', null);
        $this->migrator->add('kalendar.seo_title_en', null);
        $this->migrator->add('kalendar.seo_description', null);
        $this->migrator->add('kalendar.seo_description_en', null);
        $this->migrator->add('kalendar.seo_image', null);
    }
};
