<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Defaults mirror ui/src/sportovni-svaz.njk's hardcoded text 1:1 — same reasoning as
     * create_souteze_settings.php's own defaults.
     */
    public function up(): void
    {
        $this->migrator->add('svaz.hero_title', 'Českomoravský billiardový svaz');
        $this->migrator->add('svaz.hero_title_en', null);
        $this->migrator->add(
            'svaz.hero_text',
            'Stejně jako ostatní sporty má i poolbilliard svůj národní sportovní svaz, který zajišťuje organizaci soutěží, rozvoj sportu a reprezentaci České republiky.'
        );
        $this->migrator->add('svaz.hero_text_en', null);

        $this->migrator->add('svaz.info_title', 'Českomoravský billiardový svaz');
        $this->migrator->add('svaz.info_title_en', null);
        $this->migrator->add(
            'svaz.info_text',
            '<p>Český poolbilliard sdružuje desítky klubů a stovky aktivních hráčů napříč republikou. ČMBS vytváří podmínky pro fungování soutěží, podporuje kluby, rozvoj mládeže i reprezentaci České republiky na mezinárodní scéně.</p><p>Společně budujeme prostředí, ve kterém může růst rekreační i vrcholový poolbilliard.</p>'
        );
        $this->migrator->add('svaz.info_text_en', null);

        $this->migrator->add('svaz.cmbs_website_url', '#');
        $this->migrator->add('svaz.cmbs_bylaws_url', '#');

        $this->migrator->add('svaz.tasks_title', 'Potřebuji vyřídit …');
        $this->migrator->add('svaz.tasks_title_en', null);
        $this->migrator->add('svaz.tasks', [
            ['text' => 'Registrace nového klubu', 'text_en' => null, 'url' => '#'],
            ['text' => 'Přestup hráče do jiného klubu', 'text_en' => null, 'url' => '#'],
            ['text' => 'Hostování hráče do jiného klubu', 'text_en' => null, 'url' => '#'],
        ]);

        $this->migrator->add('svaz.committee_title', 'Výkonný výbor sekce Český poolbilliard');
        $this->migrator->add('svaz.committee_title_en', null);
        $this->migrator->add('svaz.committee_email', 'vvs.pool@cmbs.cz');
        $this->migrator->add('svaz.committee_iban', '212931827/0600');

        $this->migrator->add('svaz.documents_title', 'Dokumenty sekce Český poolbilliard');
        $this->migrator->add('svaz.documents_title_en', null);

        $this->migrator->add('svaz.notices_title', 'Zprávy výkonného výboru');
        $this->migrator->add('svaz.notices_title_en', null);
        $this->migrator->add('svaz.notices_subtitle', 'Oficiální rozhodnutí a důležité informace');
        $this->migrator->add('svaz.notices_subtitle_en', null);
    }
};
