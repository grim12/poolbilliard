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
            'nav_label_en' => 'Regions',
            'eyebrow' => 'Regionální liga jednotlivců',
            'eyebrow_en' => 'Regional individual league',
            'title' => 'Oblastní soutěže jednotlivců',
            'title_en' => 'Regional individual competitions',
            'body' => '<h3>Ideální místo, kde začít soutěžit</h3>'
                .'<p>Regionální soutěže představují nejpřístupnější úroveň českého soutěžního poolbilliardu. Jsou vhodné zejména pro začínající a klubové hráče, kteří chtějí získat první turnajové zkušenosti a poměřovat se s hráči ze svého regionu.</p>'
                .'<p>Za jednotlivé turnaje hráči získávají body do regionálního žebříčku. Nejlepší hráči jednotlivých regionů se následně kvalifikují na celostátní Finále regionů, které se zpravidla koná v červnu.</p>',
            'body_en' => '<h3>The ideal place to start competing</h3>'
                .'<p>Regional competitions are the most accessible level of Czech competitive pool billiards. They\'re especially suited to beginner and club players who want to gain their first tournament experience and measure themselves against players from their own region.</p>'
                .'<p>Players earn points toward the regional ranking at each tournament. The best players from each region then qualify for the national Regional Final, usually held in June.</p>',
        ],
        [
            'anchor' => 'cpt',
            'nav_label' => 'Česká poolová tour',
            'nav_label_en' => 'Czech Pool Tour',
            'eyebrow' => 'Nejvyšší celostátní turnajová série',
            'eyebrow_en' => 'The top national tournament series',
            'title' => 'Česká poolová tour',
            'title_en' => 'Czech Pool Tour',
            'body' => '<p>Česká poolová tour (ČPT) představuje nejvyšší pravidelnou celostátní soutěž jednotlivců. Během jarní části sezóny se odehrají 4 otevřené turnaje, na kterých se nejlepší čeští hráči pravidelně potkávají v konkurenci hráčů z celé republiky.</p>'
                .'<p>Turnaje jsou otevřené, účast tedy není podmíněna postupem z regionálních soutěží. Hraje se nejen o body do žebříčku a turnajové trofeje, ale také o <strong>prize money</strong>. Výsledky jednotlivých turnajů vytvářejí samostatný žebříček České poolové tour.</p>'
                .'<p><strong>Czech Pool Masters</strong> — vyvrcholením České poolové tour je uzavřený turnaj Czech Pool Masters, který se zpravidla koná v červnu. Právo startovat získává 16 nejlepších hráčů celkového žebříčku České poolové tour. Na rozdíl od otevřených turnajů ČPT se tak na Masters proti sobě postaví pouze nejlepší hráči celé série.</p>',
            'body_en' => '<p>The Czech Pool Tour (ČPT) is the top regular national individual competition. Four open tournaments are held during the spring part of the season, where the best Czech players regularly meet in a field drawn from across the whole country.</p>'
                .'<p>The tournaments are open — entry isn\'t conditional on qualifying through regional competitions. Players compete not only for ranking points and tournament trophies, but also for <strong>prize money</strong>. Results from each tournament feed into a separate Czech Pool Tour ranking.</p>'
                .'<p><strong>Czech Pool Masters</strong> — the Czech Pool Tour culminates in the closed Czech Pool Masters tournament, usually held in June. The right to compete goes to the 16 best players in the overall Czech Pool Tour ranking. Unlike the open ČPT tournaments, only the series\' very best players face off at the Masters.</p>',
        ],
        [
            'anchor' => 'mcr-jednotlivcu',
            'nav_label' => 'MČR jednotlivců',
            'nav_label_en' => 'Czech Championship',
            'eyebrow' => 'Vrchol domácí sezóny',
            'eyebrow_en' => 'The peak of the domestic season',
            'title' => 'Mistrovství České republiky jednotlivců',
            'title_en' => 'Czech Individual Championship',
            'body' => '<p>Mistrovství České republiky jednotlivců patří společně s MČR týmů k nejprestižnějším soutěžím českého poolbilliardu. O mistrovské tituly se hraje ve všech čtyřech hlavních disciplínách: 8-ball, 9-ball, 10-ball a 14.1 nekonečná.</p>'
                .'<p><strong>32 hráčů na MČR jednotlivců</strong></p>'
                .'<p><strong>16 — Přímý postup:</strong> aktuálně nejlepší hráči podle celostátního žebříčku získávají přímé právo startu na MČR.</p>'
                .'<p><strong>16 — Kvalifikace:</strong> dalších šestnáct míst je určeno hráčům, kteří si účast vybojují v kvalifikaci před samotným MČR.</p>'
                .'<p><strong>Od MČR k české reprezentaci</strong> — Mistrovství České republiky není pouze souboj o domácí titul. Na základě celkového žebříčku, výsledků MČR a dalších kritérií stanovených výkonným výborem svazu jsou nominováni hráči, kteří reprezentují Českou republiku na vrcholných mezinárodních soutěžích.</p>',
            'body_en' => '<p>The Czech Individual Championship, alongside the Czech Team Championship, is among the most prestigious competitions in Czech pool billiards. Championship titles are contested in all four main disciplines: 8-ball, 9-ball, 10-ball, and 14.1 straight pool.</p>'
                .'<p><strong>32 players at the Czech Individual Championship</strong></p>'
                .'<p><strong>16 — Direct qualification:</strong> the currently best players by the national ranking earn a direct right to start at the championship.</p>'
                .'<p><strong>16 — Qualification:</strong> the other sixteen spots go to players who earn their place in a qualifier held before the championship itself.</p>'
                .'<p><strong>From the Czech Championship to the national team</strong> — the Czech Championship isn\'t just a contest for a domestic title. Based on the overall ranking, championship results, and other criteria set by the federation\'s executive committee, players are nominated to represent the Czech Republic at top international competitions.</p>',
            'aside' => '<p>Kdo patří mezi nejlepší hráče v Česku?</p>'
                .'<h3>Celoroční žebříček</h3>'
                .'<p>Vedle žebříčků jednotlivých soutěžních sérií existuje také celostátní žebříček jednotlivců. Do něj se započítávají zejména výsledky z České poolové tour a Mistrovství ČR.</p>'
                .'<p>Žebříček funguje na průběžném principu posledních 365 dní. Nové výsledky se započítávají a výsledky starší než 365 dní se postupně odmazávají. Celostátní žebříček tak průběžně ukazuje aktuální výkonnost a postavení hráčů v českém poolbilliardu a má přímý vliv na účast na Mistrovství České republiky.</p>',
            'aside_en' => '<p>Who ranks among the best players in the Czech Republic?</p>'
                .'<h3>Annual ranking</h3>'
                .'<p>Alongside the rankings of individual competition series, there is also a national individual ranking. It primarily counts results from the Czech Pool Tour and the Czech Championship.</p>'
                .'<p>The ranking runs on a rolling 365-day basis. New results are added in, and results older than 365 days are gradually dropped. The national ranking thus continuously reflects players\' current form and standing in Czech pool billiards, and directly affects participation in the Czech Championship.</p>',
        ],
        [
            'anchor' => 'mcr-kategorie',
            'nav_label' => 'MČR v kategoriích',
            'nav_label_en' => 'Championship categories',
            'eyebrow' => 'Mistrovské kategorie',
            'eyebrow_en' => 'Championship categories',
            'title' => 'Mistrovství ČR v kategoriích',
            'title_en' => 'Czech Championship by category',
            'body' => '<p>Tituly mistrů České republiky se nerozdávají pouze v hlavní kategorii jednotlivců. Vedle hlavního MČR se konají mistrovství v juniorské, ženské a veteránské kategorii, dále MČR dvojic, smíšených dvojic a týmů.</p>',
            'body_en' => "<p>Czech Championship titles aren't awarded only in the main individual category. Alongside the main championship, titles are also contested in the junior, women's, and veterans' categories, as well as doubles, mixed doubles, and teams.</p>",
            'below' => '<ul><li>Junioři</li><li>Ženy</li><li>Veteráni</li><li>MČR dvojic</li><li>MČR smíšených dvojic</li><li>MČR týmů</li></ul>',
            'below_en' => '<ul><li>Juniors</li><li>Women</li><li>Veterans</li><li>Doubles Championship</li><li>Mixed Doubles Championship</li><li>Team Championship</li></ul>',
        ],
        [
            'anchor' => 'junior-open',
            'nav_label' => 'Junioři',
            'nav_label_en' => 'Juniors',
            'eyebrow' => 'Junior Open',
            'eyebrow_en' => 'Junior Open',
            'title' => 'Juniorské soutěže',
            'title_en' => 'Junior competitions',
            'body' => '<p>Základ pravidelného soutěžního programu mladých hráčů tvoří série Junior Open. Během sezóny se odehraje celkem 4 kola, na kterých junioři získávají turnajové zkušenosti a body do juniorského žebříčku.</p>'
                .'<p>Výsledky série mají následně vliv na nominace na Mistrovství ČR juniorů a na výběr hráčů do české juniorské reprezentace.</p>',
            'body_en' => '<p>The Junior Open series forms the backbone of the regular competitive program for young players. Four rounds are held during the season, where juniors gain tournament experience and earn points toward the junior ranking.</p>'
                .'<p>Results from the series subsequently influence nominations to the Czech Junior Championship and the selection of players for the Czech junior national team.</p>',
        ],
        [
            'anchor' => 'tymy',
            'nav_label' => 'Týmy',
            'nav_label_en' => 'Teams',
            'eyebrow' => 'Klub proti klubu',
            'eyebrow_en' => 'Club versus club',
            'title' => 'Týmové soutěže',
            'title_en' => 'Team competitions',
            'body' => '<p>Samostatnou část českého soutěžního systému tvoří týmové ligové soutěže. Hráči v nich nereprezentují pouze sami sebe, ale především svůj klub. Ligový systém tvoří tři úrovně: Extraliga, 1. liga a 2. liga.</p>'
                .'<p>Každou ligu tvoří 16 týmů a v průběhu jarní části sezóny se týmy utkávají systémem každý s každým. Na rozdíl od soutěží jednotlivců zde existuje skutečný ligový systém s možností pohybu mezi jednotlivými úrovněmi.</p>'
                .'<p><strong>Postupy, sestupy a baráž</strong></p>'
                .'<p>Poslední 4 týmy Extraligy hrají baráž s prvními 4 týmy 1. ligy, poslední 4 týmy 1. ligy s prvními 4 týmy 2. ligy. Baráž rozhoduje, které týmy si ligovou příslušnost udrží a které se posunou o úroveň výše nebo níže.</p>'
                .'<p><strong>Vrchol klubové sezóny MČR týmů</strong></p>'
                .'<p>Pro nejúspěšnější týmy Extraligy sezóna nekončí posledním ligovým kolem. 8 nejlepších týmů Extraligy postupuje na Mistrovství České republiky týmů. Zisk týmového mistrovského titulu představuje jednu z nejprestižnějších trofejí, kterou může český poolbilliardový klub získat.</p>',
            'body_en' => "<p>Team league competitions form a distinct part of the Czech competitive system. Players don't just represent themselves in them, but above all their club. The league system has three tiers: the Extraliga, 1st League, and 2nd League.</p>"
                .'<p>Each league has 16 teams, and during the spring part of the season teams play each other in a round-robin format. Unlike individual competitions, there\'s a genuine league system here, with the possibility of moving between tiers.</p>'
                .'<p><strong>Promotion, relegation, and playoffs</strong></p>'
                .'<p>The bottom 4 Extraliga teams play a promotion/relegation playoff against the top 4 teams of the 1st League, and the bottom 4 teams of the 1st League play the top 4 teams of the 2nd League. The playoff decides which teams keep their league status and which move up or down a tier.</p>'
                .'<p><strong>The peak of the club season — the Team Championship</strong></p>'
                ."<p>For the most successful Extraliga teams, the season doesn't end with the final league round. The top 8 Extraliga teams advance to the Czech Team Championship. Winning the team championship title is one of the most prestigious trophies a Czech pool billiards club can earn.</p>",
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::SECTIONS as $index => $section) {
            foreach (['nav_label', 'eyebrow', 'title', 'body', 'aside', 'below'] as $field) {
                if (array_key_exists($field, $section)) {
                    $section[$field] = ['cs' => $section[$field], 'en' => $section["{$field}_en"] ?? null];
                }
                unset($section["{$field}_en"]);
            }

            CompetitionSection::updateOrCreate(
                ['anchor' => $section['anchor']],
                [...$section, 'sort_order' => $index]
            );
        }
    }
}
