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
            'myth_text_en' => 'You have to pot the black in the opposite pocket — or the same one your last ball went into.',
            'correct_text' => 'Černá se hraje do <strong>libovolné kapsy, kterou předem nahlásíte</strong>. Když ji nepotopíte, pokračuje soupeř. Černá potopená do nehlášené kapsy nebo s faulem znamená prohru. Pravidlo „protější kapsy“ je přežitek z dob žetonových stolů.',
            'correct_text_en' => 'The black is played into <strong>any pocket you call beforehand</strong>. If you miss it, your opponent continues. Potting the black in an uncalled pocket, or with a foul, means a loss. The "opposite pocket" rule is a holdover from coin-operated tables.',
        ],
        [
            'myth_text' => 'Po faulu má soupeř dvě rány a musí hrát přes půlku stolu.',
            'myth_text_en' => 'After a foul, your opponent gets two shots and must play across the half-table line.',
            'correct_text' => 'Po faulu dostane soupeř bílou kouli <strong>„do ruky“</strong> — může ji položit kamkoliv na stole a pokračuje normálně. Žádné dvě rány, žádná povinnost hrát přes půlku.',
            'correct_text_en' => 'After a foul, your opponent gets <strong>ball-in-hand</strong> — they can place the cue ball anywhere on the table and play continues normally. No two shots, no half-table rule.',
        ],
        [
            'myth_text' => 'Když je bílá koule nalepená na mantinelu, mohu si ji odsunout na šířku tága.',
            'myth_text_en' => "If the cue ball is frozen against the rail, I can nudge it away by a cue's width.",
            'correct_text' => 'Není to pravda. I když je bílá přímo u mantinelu (tzv. frozen), hraje se běžně dál.',
            'correct_text_en' => "That's not true. Even when the cue ball is frozen against the rail, play simply continues as normal.",
        ],
        [
            'myth_text' => 'Když potopím soupeřovu kouli, je to automaticky faul.',
            'myth_text_en' => "If I pot my opponent's ball, it's automatically a foul.",
            'correct_text' => 'Není. Pokud při strku nejdříve správně zasáhnete svou kouli a splníte ostatní podmínky platného strku, může při něm spadnout i soupeřova koule. Ta zůstává v kapse a hra pokračuje podle výsledku strku.',
            'correct_text_en' => "It isn't. As long as you first legally hit your own ball and meet the other requirements of a legal shot, your opponent's ball can also drop. It stays potted, and play continues based on the result of the shot.",
        ],
        [
            'myth_text' => 'Když při rozstřelu spadne černá, automaticky jsem vyhrál.',
            'myth_text_en' => 'If the black drops on the break, I automatically win.',
            'correct_text' => 'Potopení osmičky při rozstřelu neznamená automatické vítězství. Černá koule se vyndavá zpět na bod v trojúhelníku a rozstřel se dál vyhodnocuje podle standardních pravidel.',
            'correct_text_en' => "Potting the 8-ball on the break doesn't mean an automatic win. The black is respotted on the foot spot, and the break is otherwise scored under the standard rules.",
        ],
        [
            'myth_text' => 'Po rozstřelu mám již vybrané celé nebo půlky podle toho, co mi spadlo.',
            'myth_text_en' => "After the break, I'm already assigned stripes or solids based on what dropped.",
            'correct_text' => 'Nemám. Hráč, který jde po rozstřelu na stůl si vybírá půlky nebo celé nezávisle na rozstřelu. Dokud jeden z hráčů nepotvrdí výběr legálním potopením dané koule, nikdo nemá nic vybrané.',
            'correct_text_en' => "You're not. Whoever shoots next after the break chooses stripes or solids independently of the break. Until a player confirms their choice by legally potting a ball of that group, nobody is assigned anything.",
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
                [
                    'myth_text' => ['cs' => $myth['myth_text'], 'en' => $myth['myth_text_en']],
                    'correct_text' => ['cs' => $myth['correct_text'], 'en' => $myth['correct_text_en']],
                    'sort_order' => $index,
                ]
            );
        }
    }
}
