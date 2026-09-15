<?php

namespace Database\Seeders;

use App\Models\Myth;
use Illuminate\Database\Seeder;

class MythSeeder extends Seeder
{
    /**
     * Mirrors ui/src/pravidla.njk's mythItems (6 myth/fact pairs, previously seeded via
     * PravidlaSettings's own migration before myths became their own entity — see
     * skills/web-component-guide.md).
     */
    private const MYTHS = [
        [
            'myth_text' => 'Černou musím potopit do protější kapsy — nebo do té samé, kam šla moje poslední koule.',
            'correct_text' => 'Černá se hraje do <strong>libovolné kapsy, kterou předem nahlásíte</strong>. Když ji nepotopíte, pokračuje soupeř. Černá potopená do nehlášené kapsy nebo s faulem znamená prohru. Pravidlo „protější kapsy“ je přežitek z dob žetonových stolů.',
        ],
        [
            'myth_text' => 'Po faulu má soupeř dvě rány a musí hrát přes půlku stolu.',
            'correct_text' => 'Po faulu dostane soupeř bílou kouli <strong>„do ruky“</strong> — může ji položit kamkoliv na stole a pokračuje normálně. Žádné dvě rány, žádná povinnost hrát přes půlku.',
        ],
        [
            'myth_text' => 'Když je bílá koule nalepená na mantinelu, mohu si ji odsunout na šířku tága.',
            'correct_text' => 'Není to pravda. I když je bílá přímo u mantinelu (tzv. frozen), hraje se běžně dál.',
        ],
        [
            'myth_text' => 'Když potopím soupeřovu kouli, je to automaticky faul.',
            'correct_text' => 'Není. Pokud při strku nejdříve správně zasáhnete svou kouli a splníte ostatní podmínky platného strku, může při něm spadnout i soupeřova koule. Ta zůstává v kapse a hra pokračuje podle výsledku strku.',
        ],
        [
            'myth_text' => 'Když při rozstřelu spadne černá, automaticky jsem vyhrál.',
            'correct_text' => 'Potopení osmičky při rozstřelu neznamená automatické vítězství. Černá koule se vyndavá zpět na bod v trojúhelníku a rozstřel se dál vyhodnocuje podle standardních pravidel.',
        ],
        [
            'myth_text' => 'Po rozstřelu mám již vybrané celé nebo půlky podle toho, co mi spadlo.',
            'correct_text' => 'Nemám. Hráč, který jde po rozstřelu na stůl si vybírá půlky nebo celé nezávisle na rozstřelu. Dokud jeden z hráčů nepotvrdí výběr legálním potopením dané koule, nikdo nemá nic vybrané.',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::MYTHS as $index => $myth) {
            Myth::updateOrCreateByTranslation(
                'myth_text',
                $myth['myth_text'],
                [...$myth, 'sort_order' => $index]
            );
        }
    }
}
