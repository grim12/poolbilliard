<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('faq.seo_title', null);
        $this->migrator->add('faq.seo_title_en', null);
        $this->migrator->add('faq.seo_description', null);
        $this->migrator->add('faq.seo_description_en', null);
        $this->migrator->add('faq.seo_image', null);
    }
};
