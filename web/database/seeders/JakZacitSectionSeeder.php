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
            'nav_label_en' => "I'm a beginner",
            'eyebrow' => 'Úplný začátečník',
            'eyebrow_en' => 'Complete beginner',
            'title' => 'Chci si poolbilliard zkusit s kamarády',
            'title_en' => 'I want to try pool billiards with friends',
            'intro' => '<p>Nejsnadnější cesta k poolbilliardu? Najděte hernu ve svém okolí, vezměte partu kamarádů a přijďte si zahrát. Žádné členství, žádné vybavení — jen zábava.</p>',
            'intro_en' => '<p>The easiest way into pool billiards? Find a venue near you, grab a group of friends, and come play. No membership, no equipment — just fun.</p>',
            'steps' => [
                ['icon' => 'map-pin', 'title' => '1. Najděte hernu ve svém okolí', 'title_en' => '1. Find a venue near you', 'text' => '<p>Vyberte si z desítek <a href="/herny/">kulečníkových heren</a> po celé republice.</p>', 'text_en' => '<p>Choose from dozens of <a href="/en/venues">billiards venues</a> across the country.</p>'],
                ['icon' => 'calendar-days', 'title' => '2. Rezervujte si stůl', 'title_en' => '2. Book a table', 'text' => '<p>Zavolejte nebo napište herně dopředu. Některé nabízejí i online rezervaci.</p>', 'text_en' => '<p>Call or message the venue ahead of time. Some also offer online booking.</p>'],
                ['icon' => 'users', 'title' => '3. Vezměte partu kamarádů', 'title_en' => '3. Bring a group of friends', 'text' => '<p>Nejvíc zábavy je ve skupině 2–4 hráčů na jeden stůl.</p>', 'text_en' => "<p>It's most fun with a group of 2–4 players per table.</p>"],
                ['icon' => 'book-open', 'title' => '4. Přečtěte si pravidla', 'title_en' => '4. Read up on the rules', 'text' => '<p>Seznamte se se základními pravidly disciplíny, kterou si chcete zahrát.</p>', 'text_en' => '<p>Get familiar with the basic rules of the discipline you want to play.</p>'],
                ['icon' => 'trophy', 'title' => '5. Přijďte si zahrát', 'title_en' => '5. Come play', 'text' => '<p>Personál vám zapůjčí vše potřebné. Za pár minut už hrajete.</p>', 'text_en' => "<p>Staff will lend you everything you need. You'll be playing within minutes.</p>"],
            ],
            'aside_panel_title' => 'Kulečníkové herny',
            'aside_panel_title_en' => 'Billiards venues',
            'aside_panel_text' => 'Desítky kulečníkových heren po celé republice. Najděte tu nejbližší a zarezervujte si stůl.',
            'aside_panel_text_en' => 'Dozens of billiards venues across the country. Find the nearest one and book a table.',
            'aside_panel_button_text' => 'Kulečníkové herny',
            'aside_panel_button_text_en' => 'Billiards venues',
            'aside_panel_button_url' => '/herny/',
            'aside_card_eyebrow' => 'Pravidla kulečníku',
            'aside_card_eyebrow_en' => 'Billiards rules',
            'aside_card_title' => 'Chcete hrát tak, jako na soutěžích?',
            'aside_card_title_en' => 'Want to play the way tournaments do?',
            'aside_card_text' => 'Kolem pravidel koluje spousta špatných informací, podívejte se na správné verze tak, jak je uznává česká i evropská poolbilliardová federace.',
            'aside_card_text_en' => 'A lot of misinformation circulates about the rules — check the correct versions as recognized by both the Czech and European pool billiards federations.',
            'aside_card_button_text' => 'Pravidla poolbilliardu',
            'aside_card_button_text_en' => 'Pool billiards rules',
            'aside_card_button_url' => '/pravidla/',
            'faq_title' => 'Nejčastější otázky začátečníků',
            'faq_title_en' => 'Frequently asked questions for beginners',
            'sort_order' => 0,
        ],
        [
            'anchor' => 'rekreacni-hrac',
            'nav_label' => 'Jsem rekreační hráč',
            'nav_label_en' => "I'm a recreational player",
            'eyebrow' => 'Rekreační hráč',
            'eyebrow_en' => 'Recreational player',
            'title' => 'Už hraji s kamarády',
            'title_en' => 'I already play with friends',
            'intro' => '<p>Pokud vás baví hra a chcete se zlepšit, klub vám otevře dveře do soutěžního poolbilliardu.</p>',
            'intro_en' => '<p>If you enjoy the game and want to improve, a club opens the door to competitive pool billiards.</p>',
            'steps' => [
                ['icon' => 'map-pin', 'title' => '1. Najděte si klub ve svém okolí', 'title_en' => '1. Find a club near you', 'text' => '<p>Vyberte si z desítek <a href="/kluby/">registrovaných klubů</a> po celé republice.</p>', 'text_en' => '<p>Choose from dozens of <a href="/en/clubs">registered clubs</a> across the country.</p>'],
                ['icon' => 'chat-bubble-left-right', 'title' => '2. Kontaktujte ambasadora klubu a domluvte se na členství', 'title_en' => "2. Contact the club's ambassador and arrange membership", 'text' => '<p>Ambasador vám ochotně poradí, jak s klubem, tréninky i vstupem do komunity.</p>', 'text_en' => '<p>The ambassador will happily advise you on the club, training, and joining the community.</p>'],
                ['icon' => 'academic-cap', 'title' => '3. Začněte trénovat, zlepšujte se. Využívejte výhody komunity', 'title_en' => '3. Start training, improve, and make the most of the community', 'text' => '<p>V klubu trénujete s ostatními hráči, sdílíte zkušenosti a rady.</p>', 'text_en' => '<p>At the club you train alongside other players, sharing experience and advice.</p>'],
                ['icon' => 'trophy', 'title' => '4. Zapojte se na turnaje a soutěže', 'title_en' => '4. Take part in tournaments and competitions', 'text' => '<p>Regionální turnaje, ligy i celostátní soutěže jsou otevřené i pro začínající hráče.</p>', 'text_en' => '<p>Regional tournaments, leagues, and national competitions are open to beginners too.</p>'],
                ['icon' => 'flag', 'title' => '5. Vyhrávejte!', 'title_en' => '5. Start winning!', 'text' => '<p>Soustavným tréninkem a účastí na turnajích se posunete a proniknete i výše v žebříčku.</p>', 'text_en' => "<p>With consistent training and tournament play, you'll progress and climb higher in the ranking.</p>"],
            ],
            'aside_panel_title' => 'Kulečníkové kluby',
            'aside_panel_title_en' => 'Billiards clubs',
            'aside_panel_text' => 'Desítky registrovaných klubů po celé republice. Najděte ten nejbližší a spojte se s ambasadorem.',
            'aside_panel_text_en' => 'Dozens of registered clubs across the country. Find the nearest one and connect with its ambassador.',
            'aside_panel_button_text' => 'Kulečníkové kluby',
            'aside_panel_button_text_en' => 'Billiards clubs',
            'aside_panel_button_url' => '/kluby/',
            'aside_card_eyebrow' => 'Turnaje',
            'aside_card_eyebrow_en' => 'Tournaments',
            'aside_card_title' => 'Chcete si zahrát soutěžně?',
            'aside_card_title_en' => 'Want to play competitively?',
            'aside_card_text' => 'Podívejte se na aktuální kalendář turnajů a soutěží pro všechny výkonnostní úrovně, od regionálních lig po celostátní tour.',
            'aside_card_text_en' => 'Check out the current calendar of tournaments and competitions for every skill level, from regional leagues to the national tour.',
            'aside_card_button_text' => 'Kalendář turnajů',
            'aside_card_button_text_en' => 'Tournament calendar',
            'aside_card_button_url' => '/kalendar/',
            'faq_title' => 'Nejčastější otázky rekreačních hráčů',
            'faq_title_en' => 'Frequently asked questions for recreational players',
            'sort_order' => 1,
        ],
        [
            'anchor' => 'rodic',
            'nav_label' => 'Jsem rodič',
            'nav_label_en' => "I'm a parent",
            'eyebrow' => 'Výchova juniorů',
            'eyebrow_en' => 'Junior development',
            'title' => 'Hledám sport pro své dítě',
            'title_en' => 'Looking for a sport for my child',
            'intro' => '<p>Sportovní výchova juniorů je absolutní prioritou svazu a klubů. Poolbilliard rozvíjí soustředění, strategické myšlení i psychickou odolnost.</p>',
            'intro_en' => '<p>Junior development is an absolute priority for both the federation and its clubs. Pool billiards builds concentration, strategic thinking, and mental resilience.</p>',
            'steps' => [
                ['icon' => 'user-group', 'title' => '1. Najděte si klub s mládežnickým programem', 'title_en' => '1. Find a club with a youth program', 'text' => '<p>Vyberte si z <a href="/kluby/">registrovaných klubů</a> po celé republice, které se věnují dětem.</p>', 'text_en' => '<p>Choose from <a href="/en/clubs">registered clubs</a> across the country that work with children.</p>'],
                ['icon' => 'chat-bubble-left-right', 'title' => '2. Kontaktujte trenéra mládeže nebo ambasadora klubu', 'title_en' => "2. Contact the youth coach or the club's ambassador", 'text' => '<p>Domluvte si první ukázkovou hodinu a proberte s trenérem možnosti.</p>', 'text_en' => '<p>Arrange a first trial lesson and discuss the options with the coach.</p>'],
                ['icon' => 'calendar-days', 'title' => '3. Přijďte na ukázkový trénink', 'title_en' => '3. Come to a trial training session', 'text' => '<p>Dítě se seznámí s kulečníkem, trenérem i atmosférou v klubu.</p>', 'text_en' => "<p>Your child gets to know the table, the coach, and the club's atmosphere.</p>"],
                ['icon' => 'academic-cap', 'title' => '4. Začněte trénovat a zúčastněte se Junior Open', 'title_en' => '4. Start training and take part in Junior Open', 'text' => '<p>Pravidelný trénink pod vedením trenéra i účast na juniorské sérii turnajů.</p>', 'text_en' => '<p>Regular training under a coach and entry into the junior tournament series.</p>'],
                ['icon' => 'trophy', 'title' => '5. Rozvíjejte talent a kvalifikujte se na Mistrovství republiky', 'title_en' => '5. Develop the talent and qualify for the national championship', 'text' => '<p>Nejlepší junioři získávají místo v juniorské reprezentaci.</p>', 'text_en' => '<p>The best juniors earn a place on the junior national team.</p>'],
            ],
            'aside_panel_title' => 'Kontakt pro juniory',
            'aside_panel_title_en' => 'Junior contact',
            'aside_panel_text' => 'Potřebujete více informací o tréninkových programech nebo juniorských akcích? Ozvěte se vedoucímu pro mládež Tomáši Vencurovi, elena.vencura@poolbilliard.cz.',
            'aside_panel_text_en' => 'Need more information about training programs or junior events? Get in touch with youth lead Tomáš Vencura at elena.vencura@poolbilliard.cz.',
            'aside_panel_button_text' => null,
            'aside_panel_button_text_en' => null,
            'aside_panel_button_url' => null,
            'aside_card_eyebrow' => 'Junior Open',
            'aside_card_eyebrow_en' => 'Junior Open',
            'aside_card_title' => 'Série turnajů pro mladé hráče',
            'aside_card_title_en' => 'A tournament series for young players',
            'aside_card_text' => 'Pravidelná juniorská série dává dětem první turnajové zkušenosti a body do juniorského žebříčku.',
            'aside_card_text_en' => 'The regular junior series gives children their first tournament experience and points toward the junior ranking.',
            'aside_card_button_text' => 'Kalendář turnajů',
            'aside_card_button_text_en' => 'Tournament calendar',
            'aside_card_button_url' => '/kalendar/',
            'faq_title' => 'Nejčastější otázky rodičů',
            'faq_title_en' => 'Frequently asked questions for parents',
            'sort_order' => 2,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::SECTIONS as $section) {
            foreach (['nav_label', 'eyebrow', 'title', 'intro', 'aside_panel_title', 'aside_panel_text', 'aside_panel_button_text', 'aside_card_eyebrow', 'aside_card_title', 'aside_card_text', 'aside_card_button_text', 'faq_title'] as $field) {
                if ($section[$field] !== null) {
                    $section[$field] = ['cs' => $section[$field], 'en' => $section["{$field}_en"] ?? null];
                }
                unset($section["{$field}_en"]);
            }

            $section['steps'] = array_map(fn (array $step) => [
                'icon' => $step['icon'],
                'title' => $step['title'],
                'title_en' => $step['title_en'],
                'text' => $step['text'],
                'text_en' => $step['text_en'],
            ], $section['steps']);

            JakZacitSection::updateOrCreate(
                ['anchor' => $section['anchor']],
                $section
            );
        }
    }
}
