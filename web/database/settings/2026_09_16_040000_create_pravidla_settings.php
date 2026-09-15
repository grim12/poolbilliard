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

        $this->migrator->add('pravidla.myths', [
            [
                'myth_text' => 'Černou musím potopit do protější kapsy — nebo do té samé, kam šla moje poslední koule.',
                'myth_text_en' => null,
                'correct_text' => 'Černá se hraje do <strong>libovolné kapsy, kterou předem nahlásíte</strong>. Když ji nepotopíte, pokračuje soupeř. Černá potopená do nehlášené kapsy nebo s faulem znamená prohru. Pravidlo „protější kapsy“ je přežitek z dob žetonových stolů.',
                'correct_text_en' => null,
            ],
            [
                'myth_text' => 'Po faulu má soupeř dvě rány a musí hrát přes půlku stolu.',
                'myth_text_en' => null,
                'correct_text' => 'Po faulu dostane soupeř bílou kouli <strong>„do ruky“</strong> — může ji položit kamkoliv na stole a pokračuje normálně. Žádné dvě rány, žádná povinnost hrát přes půlku.',
                'correct_text_en' => null,
            ],
            [
                'myth_text' => 'Když je bílá koule nalepená na mantinelu, mohu si ji odsunout na šířku tága.',
                'myth_text_en' => null,
                'correct_text' => 'Není to pravda. I když je bílá přímo u mantinelu (tzv. frozen), hraje se běžně dál.',
                'correct_text_en' => null,
            ],
            [
                'myth_text' => 'Když potopím soupeřovu kouli, je to automaticky faul.',
                'myth_text_en' => null,
                'correct_text' => 'Není. Pokud při strku nejdříve správně zasáhnete svou kouli a splníte ostatní podmínky platného strku, může při něm spadnout i soupeřova koule. Ta zůstává v kapse a hra pokračuje podle výsledku strku.',
                'correct_text_en' => null,
            ],
            [
                'myth_text' => 'Když při rozstřelu spadne černá, automaticky jsem vyhrál.',
                'myth_text_en' => null,
                'correct_text' => 'Potopení osmičky při rozstřelu neznamená automatické vítězství. Černá koule se vyndavá zpět na bod v trojúhelníku a rozstřel se dál vyhodnocuje podle standardních pravidel.',
                'correct_text_en' => null,
            ],
            [
                'myth_text' => 'Po rozstřelu mám již vybrané celé nebo půlky podle toho, co mi spadlo.',
                'myth_text_en' => null,
                'correct_text' => 'Nemám. Hráč, který jde po rozstřelu na stůl si vybírá půlky nebo celé nezávisle na rozstřelu. Dokud jeden z hráčů nepotvrdí výběr legálním potopením dané koule, nikdo nemá nic vybrané.',
                'correct_text_en' => null,
            ],
        ]);

        $this->migrator->add('pravidla.rule_cards_title', 'Pravidla podle disciplíny');
        $this->migrator->add('pravidla.rule_cards_title_en', null);
    }
};
