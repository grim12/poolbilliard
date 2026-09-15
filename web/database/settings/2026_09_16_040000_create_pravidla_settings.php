<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Defaults mirror ui/src/pravidla.njk's hardcoded text 1:1 — same reasoning as
     * create_kluby_settings.php's own defaults.
     */
    public function up(): void
    {
        $this->migrator->add('pravidla.hero_title', 'Pravidla poolbilliardu');
        $this->migrator->add('pravidla.hero_title_en', null);
        $this->migrator->add(
            'pravidla.hero_text',
            'Vyberte si disciplínu a prostudujte si oficiální pravidla. Každá hra má svá specifika, ale základní principy jsou společné.'
        );
        $this->migrator->add('pravidla.hero_text_en', null);

        $this->migrator->add('pravidla.myths_title', 'Hrajete poprvé? Nelekejte se');
        $this->migrator->add('pravidla.myths_title_en', null);
        $this->migrator->add(
            'pravidla.myths_subtitle',
            'Níže najdete kompletní <strong>soutěžní</strong> pravidla, podle kterých se hraje na turnajích a v ligách. Pro hru s kamarády v herně je <strong>nemusíte znát do detailu</strong>. Stačí se vyhnout pár nejčastějším dezinformacím, které mezi rekreačními hráči kolují. Ty hlavní jsme sepsali níže.'
        );
        $this->migrator->add('pravidla.myths_subtitle_en', null);

        $this->migrator->add('pravidla.rule_cards_title', 'Pravidla podle disciplíny');
        $this->migrator->add('pravidla.rule_cards_title_en', null);
    }
};
