<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Defaults mirror ui/src/jak-zacit.njk's hardcoded text 1:1 — same reasoning as
     * create_souteze_settings.php's own defaults.
     */
    public function up(): void
    {
        $this->migrator->add('jak_zacit.hero_title', 'Brána do světa poolbilliardu');
        $this->migrator->add('jak_zacit.hero_title_en', null);
        $this->migrator->add(
            'jak_zacit.hero_text',
            'Ať jste úplný začátečník, rekreační hráč nebo hledáte sport pro své dítě, ukážeme vám, jak začít.'
        );
        $this->migrator->add('jak_zacit.hero_text_en', null);

        $this->migrator->add('jak_zacit.feature_cards', [
            [
                'icon' => 'user-plus',
                'title' => 'Nikdy jsem kulečník nehrál',
                'title_en' => null,
                'text' => 'Chci si poolbilliard vyzkoušet a zjistit, jak začít.',
                'text_en' => null,
                'link_text' => 'Začít',
                'link_text_en' => null,
                'link_url' => '#zacatecnik',
            ],
            [
                'icon' => 'users',
                'title' => 'Už hraji s kamarády',
                'title_en' => null,
                'text' => 'Chci se zlepšit, trénovat a vyzkoušet soutěže.',
                'text_en' => null,
                'link_text' => 'Posunout svou hru',
                'link_text_en' => null,
                'link_url' => '#rekreacni-hrac',
            ],
            [
                'icon' => 'face-smile',
                'title' => 'Hledám sport pro své dítě',
                'title_en' => null,
                'text' => 'Chci kroužek, kemp, trénink nebo turnajovou akci.',
                'text_en' => null,
                'link_text' => 'Začít',
                'link_text_en' => null,
                'link_url' => '#rodic',
            ],
            [
                'icon' => 'academic-cap',
                'title' => 'Chci se zlepšit',
                'title_en' => null,
                'text' => 'Hledám trenéra, individuální trénink nebo pokročilou výuku.',
                'text_en' => null,
                'link_text' => 'Najít trenéra',
                'link_text_en' => null,
                'link_url' => '#rekreacni-hrac',
            ],
        ]);

        $this->migrator->add('jak_zacit.cta_title', 'Připraveni začít?');
        $this->migrator->add('jak_zacit.cta_title_en', null);
        $this->migrator->add(
            'jak_zacit.cta_text',
            'Vyberte si klub nebo si spojte se s jeho ambasadorem. Pomůžeme vám s prvními návštěvami i dalšími kroky.'
        );
        $this->migrator->add('jak_zacit.cta_text_en', null);

        $this->migrator->add('jak_zacit.match_form_eyebrow', 'Doporučeno na míru');
        $this->migrator->add('jak_zacit.match_form_eyebrow_en', null);
        $this->migrator->add('jak_zacit.match_form_title', 'Najdeme vám ideální klub, hernu nebo trenéra');
        $this->migrator->add('jak_zacit.match_form_title_en', null);
        $this->migrator->add(
            'jak_zacit.match_form_text',
            'Nevíte, kde začít? Zanechte nám své údaje, lokalitu a představy. My vám doporučíme nejbližší hernu, klub nebo trenéra.'
        );
        $this->migrator->add('jak_zacit.match_form_text_en', null);
    }
};
