<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('herny.seo_title', null);
        $this->migrator->add('herny.seo_title_en', null);
        $this->migrator->add('herny.seo_description', null);
        $this->migrator->add('herny.seo_description_en', null);
        $this->migrator->add('herny.seo_image', null);
    }
};
