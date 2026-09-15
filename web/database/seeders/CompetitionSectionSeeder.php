<?php

namespace Database\Seeders;

use App\Models\CompetitionSection;
use Illuminate\Database\Seeder;

class CompetitionSectionSeeder extends Seeder
{
    /**
     * Mirrors ui/src/souteze.njk's 6 contentSection() calls. Bespoke hand-authored markup
     * inside `aside`/`below` (stat breakdown rows, category tile grid, colored infoPanel()
     * cards) is flattened into plain rich text — see App\Models\CompetitionSection's doc
     * comment for why.
     */
    private const SECTIONS = [
        [
            'anchor' => 'regiony',
            'nav_label' => 'Regiony',
            'eyebrow' => 'Regionální liga jednotlivců',
            'title' => 'Oblastní soutěže jednotlivců',
            'body' => '<h3>Ideální místo, kde začít soutěžit</h3>'
                .'<p>Regionální soutěže představují nejpřístupnější úroveň českého soutěžního poolbilliardu. Jsou vhodné zejména pro začínající a klubové hráče, kteří chtějí získat první turnajové zkušenosti a poměřovat se s hráči ze svého regionu.</p>'
                .'<p>Za jednotlivé turnaje hráči získávají body do regionálního žebříčku. Nejlepší hráči jednotlivých regionů se následně kvalifikují na celostátní Finále regionů, které se zpravidla koná v červnu.</p>',
        ],
        [
            'anchor' => 'cpt',
            'nav_label' => 'Česká poolová tour',
            'eyebrow' => 'Nejvyšší celostátní turnajová série',
            'title' => 'Česká poolová tour',
            'body' => '<p>Česká poolová tour (ČPT) představuje nejvyšší pravidelnou celostátní soutěž jednotlivců. Během jarní části sezóny se odehrají 4 otevřené turnaje, na kterých se nejlepší čeští hráči pravidelně potkávají v konkurenci hráčů z celé republiky.</p>'
                .'<p>Turnaje jsou otevřené, účast tedy není podmíněna postupem z regionálních soutěží. Hraje se nejen o body do žebříčku a turnajové trofeje, ale také o <strong>prize money</strong>. Výsledky jednotlivých turnajů vytvářejí samostatný žebříček České poolové tour.</p>'
                .'<p><strong>Czech Pool Masters</strong> — vyvrcholením České poolové tour je uzavřený turnaj Czech Pool Masters, který se zpravidla koná v červnu. Právo startovat získává 16 nejlepších hráčů celkového žebříčku České poolové tour. Na rozdíl od otevřených turnajů ČPT se tak na Masters proti sobě postaví pouze nejlepší hráči celé série.</p>',
        ],
        [
            'anchor' => 'mcr-jednotlivcu',
            'nav_label' => 'MČR jednotlivců',
            'eyebrow' => 'Vrchol domácí sezóny',
            'title' => 'Mistrovství České republiky jednotlivců',
            'body' => '<p>Mistrovství České republiky jednotlivců patří společně s MČR týmů k nejprestižnějším soutěžím českého poolbilliardu. O mistrovské tituly se hraje ve všech čtyřech hlavních disciplínách: 8-ball, 9-ball, 10-ball a 14.1 nekonečná.</p>'
                .'<p><strong>32 hráčů na MČR jednotlivců</strong></p>'
                .'<p><strong>16 — Přímý postup:</strong> aktuálně nejlepší hráči podle celostátního žebříčku získávají přímé právo startu na MČR.</p>'
                .'<p><strong>16 — Kvalifikace:</strong> dalších šestnáct míst je určeno hráčům, kteří si účast vybojují v kvalifikaci před samotným MČR.</p>'
                .'<p><strong>Od MČR k české reprezentaci</strong> — Mistrovství České republiky není pouze souboj o domácí titul. Na základě celkového žebříčku, výsledků MČR a dalších kritérií stanovených výkonným výborem svazu jsou nominováni hráči, kteří reprezentují Českou republiku na vrcholných mezinárodních soutěžích.</p>',
            'aside' => '<p>Kdo patří mezi nejlepší hráče v Česku?</p>'
                .'<h3>Celoroční žebříček</h3>'
                .'<p>Vedle žebříčků jednotlivých soutěžních sérií existuje také celostátní žebříček jednotlivců. Do něj se započítávají zejména výsledky z České poolové tour a Mistrovství ČR.</p>'
                .'<p>Žebříček funguje na průběžném principu posledních 365 dní. Nové výsledky se započítávají a výsledky starší než 365 dní se postupně odmazávají. Celostátní žebříček tak průběžně ukazuje aktuální výkonnost a postavení hráčů v českém poolbilliardu a má přímý vliv na účast na Mistrovství České republiky.</p>',
        ],
        [
            'anchor' => 'mcr-kategorie',
            'nav_label' => 'MČR v kategoriích',
            'eyebrow' => 'Mistrovské kategorie',
            'title' => 'Mistrovství ČR v kategoriích',
            'body' => '<p>Tituly mistrů České republiky se nerozdávají pouze v hlavní kategorii jednotlivců. Vedle hlavního MČR se konají mistrovství v juniorské, ženské a veteránské kategorii, dále MČR dvojic, smíšených dvojic a týmů.</p>',
            'below' => '<ul><li>Junioři</li><li>Ženy</li><li>Veteráni</li><li>MČR dvojic</li><li>MČR smíšených dvojic</li><li>MČR týmů</li></ul>',
        ],
        [
            'anchor' => 'junior-open',
            'nav_label' => 'Junioři',
            'eyebrow' => 'Junior Open',
            'title' => 'Juniorské soutěže',
            'body' => '<p>Základ pravidelného soutěžního programu mladých hráčů tvoří série Junior Open. Během sezóny se odehraje celkem 4 kola, na kterých junioři získávají turnajové zkušenosti a body do juniorského žebříčku.</p>'
                .'<p>Výsledky série mají následně vliv na nominace na Mistrovství ČR juniorů a na výběr hráčů do české juniorské reprezentace.</p>',
        ],
        [
            'anchor' => 'tymy',
            'nav_label' => 'Týmy',
            'eyebrow' => 'Klub proti klubu',
            'title' => 'Týmové soutěže',
            'body' => '<p>Samostatnou část českého soutěžního systému tvoří týmové ligové soutěže. Hráči v nich nereprezentují pouze sami sebe, ale především svůj klub. Ligový systém tvoří tři úrovně: Extraliga, 1. liga a 2. liga.</p>'
                .'<p>Každou ligu tvoří 16 týmů a v průběhu jarní části sezóny se týmy utkávají systémem každý s každým. Na rozdíl od soutěží jednotlivců zde existuje skutečný ligový systém s možností pohybu mezi jednotlivými úrovněmi.</p>'
                .'<p><strong>Postupy, sestupy a baráž</strong></p>'
                .'<p>Poslední 4 týmy Extraligy hrají baráž s prvními 4 týmy 1. ligy, poslední 4 týmy 1. ligy s prvními 4 týmy 2. ligy. Baráž rozhoduje, které týmy si ligovou příslušnost udrží a které se posunou o úroveň výše nebo níže.</p>'
                .'<p><strong>Vrchol klubové sezóny MČR týmů</strong></p>'
                .'<p>Pro nejúspěšnější týmy Extraligy sezóna nekončí posledním ligovým kolem. 8 nejlepších týmů Extraligy postupuje na Mistrovství České republiky týmů. Zisk týmového mistrovského titulu představuje jednu z nejprestižnějších trofejí, kterou může český poolbilliardový klub získat.</p>',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::SECTIONS as $index => $section) {
            CompetitionSection::updateOrCreate(
                ['anchor' => $section['anchor']],
                [...$section, 'sort_order' => $index]
            );
        }
    }
}
