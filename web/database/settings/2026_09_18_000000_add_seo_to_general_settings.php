<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.seo_title_suffix', 'Český Poolbilliard');
        $this->migrator->add('general.seo_default_og_image', null);
    }
};
