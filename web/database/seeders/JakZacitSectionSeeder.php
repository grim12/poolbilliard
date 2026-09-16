<?php

namespace Database\Seeders;

use App\Models\JakZacitSection;
use Illuminate\Database\Seeder;

class JakZacitSectionSeeder extends Seeder
{
    /**
     * Mirrors the 3 {% call contentSection %} blocks in ui/src/jak-zacit.njk. `anchor` doubles
     * as the matching FaqGroup slug (see JakZacitFaqItemSeeder/FaqGroupSeeder) and as the
     * jump-nav/section id.
     */
    private const SECTIONS = [
        [
            'anchor' => 'zacatecnik',
            'nav_label' => 'Jsem začátečník',
            'eyebrow' => 'Úplný začátečník',
            'title' => 'Chci si poolbilliard zkusit s kamarády',
            'intro' => '<p>Nejsnadnější cesta k poolbilliardu? Najděte hernu ve svém okolí, vezměte partu kamarádů a přijďte si zahrát. Žádné členství, žádné vybavení — jen zábava.</p>',
            'steps' => [
                ['icon' => 'map-pin', 'title' => '1. Najděte hernu ve svém okolí', 'text' => '<p>Vyberte si z desítek <a href="/herny/">kulečníkových heren</a> po celé republice.</p>'],
                ['icon' => 'calendar-days', 'title' => '2. Rezervujte si stůl', 'text' => '<p>Zavolejte nebo napište herně dopředu. Některé nabízejí i online rezervaci.</p>'],
                ['icon' => 'users', 'title' => '3. Vezměte partu kamarádů', 'text' => '<p>Nejvíc zábavy je ve skupině 2–4 hráčů na jeden stůl.</p>'],
                ['icon' => 'book-open', 'title' => '4. Přečtěte si pravidla', 'text' => '<p>Seznamte se se základními pravidly disciplíny, kterou si chcete zahrát.</p>'],
                ['icon' => 'trophy', 'title' => '5. Přijďte si zahrát', 'text' => '<p>Personál vám zapůjčí vše potřebné. Za pár minut už hrajete.</p>'],
            ],
            'aside_panel_title' => 'Kulečníkové herny',
            'aside_panel_text' => 'Desítky kulečníkových heren po celé republice. Najděte tu nejbližší a zarezervujte si stůl.',
            'aside_panel_button_text' => 'Kulečníkové herny',
            'aside_panel_button_url' => '/herny/',
            'aside_card_eyebrow' => 'Pravidla kulečníku',
            'aside_card_title' => 'Chcete hrát tak, jako na soutěžích?',
            'aside_card_text' => 'Kolem pravidel koluje spousta špatných informací, podívejte se na správné verze tak, jak je uznává česká i evropská poolbilliardová federace.',
            'aside_card_button_text' => 'Pravidla poolbilliardu',
            'aside_card_button_url' => '/pravidla/',
            'faq_title' => 'Nejčastější otázky začátečníků',
            'sort_order' => 0,
        ],
        [
            'anchor' => 'rekreacni-hrac',
            'nav_label' => 'Jsem rekreační hráč',
            'eyebrow' => 'Rekreační hráč',
            'title' => 'Už hraji s kamarády',
            'intro' => '<p>Pokud vás baví hra a chcete se zlepšit, klub vám otevře dveře do soutěžního poolbilliardu.</p>',
            'steps' => [
                ['icon' => 'map-pin', 'title' => '1. Najděte si klub ve svém okolí', 'text' => '<p>Vyberte si z desítek <a href="/kluby/">registrovaných klubů</a> po celé republice.</p>'],
                ['icon' => 'chat-bubble-left-right', 'title' => '2. Kontaktujte ambasadora klubu a domluvte se na členství', 'text' => '<p>Ambasador vám ochotně poradí, jak s klubem, tréninky i vstupem do komunity.</p>'],
                ['icon' => 'academic-cap', 'title' => '3. Začněte trénovat, zlepšujte se. Využívejte výhody komunity', 'text' => '<p>V klubu trénujete s ostatními hráči, sdílíte zkušenosti a rady.</p>'],
                ['icon' => 'trophy', 'title' => '4. Zapojte se na turnaje a soutěže', 'text' => '<p>Regionální turnaje, ligy i celostátní soutěže jsou otevřené i pro začínající hráče.</p>'],
                ['icon' => 'flag', 'title' => '5. Vyhrávejte!', 'text' => '<p>Soustavným tréninkem a účastí na turnajích se posunete a proniknete i výše v žebříčku.</p>'],
            ],
            'aside_panel_title' => 'Kulečníkové kluby',
            'aside_panel_text' => 'Desítky registrovaných klubů po celé republice. Najděte ten nejbližší a spojte se s ambasadorem.',
            'aside_panel_button_text' => 'Kulečníkové kluby',
            'aside_panel_button_url' => '/kluby/',
            'aside_card_eyebrow' => 'Turnaje',
            'aside_card_title' => 'Chcete si zahrát soutěžně?',
            'aside_card_text' => 'Podívejte se na aktuální kalendář turnajů a soutěží pro všechny výkonnostní úrovně, od regionálních lig po celostátní tour.',
            'aside_card_button_text' => 'Kalendář turnajů',
            'aside_card_button_url' => '/kalendar/',
            'faq_title' => 'Nejčastější otázky rekreačních hráčů',
            'sort_order' => 1,
        ],
        [
            'anchor' => 'rodic',
            'nav_label' => 'Jsem rodič',
            'eyebrow' => 'Výchova juniorů',
            'title' => 'Hledám sport pro své dítě',
            'intro' => '<p>Sportovní výchova juniorů je absolutní prioritou svazu a klubů. Poolbilliard rozvíjí soustředění, strategické myšlení i psychickou odolnost.</p>',
            'steps' => [
                ['icon' => 'user-group', 'title' => '1. Najděte si klub s mládežnickým programem', 'text' => '<p>Vyberte si z <a href="/kluby/">registrovaných klubů</a> po celé republice, které se věnují dětem.</p>'],
                ['icon' => 'chat-bubble-left-right', 'title' => '2. Kontaktujte trenéra mládeže nebo ambasadora klubu', 'text' => '<p>Domluvte si první ukázkovou hodinu a proberte s trenérem možnosti.</p>'],
                ['icon' => 'calendar-days', 'title' => '3. Přijďte na ukázkový trénink', 'text' => '<p>Dítě se seznámí s kulečníkem, trenérem i atmosférou v klubu.</p>'],
                ['icon' => 'academic-cap', 'title' => '4. Začněte trénovat a zúčastněte se Junior Open', 'text' => '<p>Pravidelný trénink pod vedením trenéra i účast na juniorské sérii turnajů.</p>'],
                ['icon' => 'trophy', 'title' => '5. Rozvíjejte talent a kvalifikujte se na Mistrovství republiky', 'text' => '<p>Nejlepší junioři získávají místo v juniorské reprezentaci.</p>'],
            ],
            'aside_panel_title' => 'Kontakt pro juniory',
            'aside_panel_text' => 'Potřebujete více informací o tréninkových programech nebo juniorských akcích? Ozvěte se vedoucímu pro mládež Tomáši Vencurovi, elena.vencura@poolbilliard.cz.',
            'aside_panel_button_text' => null,
            'aside_panel_button_url' => null,
            'aside_card_eyebrow' => 'Junior Open',
            'aside_card_title' => 'Série turnajů pro mladé hráče',
            'aside_card_text' => 'Pravidelná juniorská série dává dětem první turnajové zkušenosti a body do juniorského žebříčku.',
            'aside_card_button_text' => 'Kalendář turnajů',
            'aside_card_button_url' => '/kalendar/',
            'faq_title' => 'Nejčastější otázky rodičů',
            'sort_order' => 2,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::SECTIONS as $section) {
            JakZacitSection::updateOrCreate(
                ['anchor' => $section['anchor']],
                $section
            );
        }
    }
}
