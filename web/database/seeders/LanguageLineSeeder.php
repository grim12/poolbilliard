<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\TranslationLoader\LanguageLine;

/**
 * Seeds every sitewide UI string translated via a plain `__('Nějaký český text')` call in Blade
 * (nav/footer chrome, aria-labels, button labels...) — replaces lang/en.json, which used to be
 * the only place these lived (see App\Filament\Resources\Translations\TranslationResource's
 * docblock for how the DB-backed loader takes over from here). Add a new pair here whenever a
 * new hardcoded string gets wrapped in __() — the admin can then edit its English translation
 * (and, optionally, override the Czech) without another deploy.
 */
class LanguageLineSeeder extends Seeder
{
    private const LINES = [
        'Novinky' => 'News',
        'Články' => 'Articles',
        'Zprávy výkonného výboru' => 'Executive Committee News',
        'Kalendář' => 'Calendar',
        'Kde hrát' => 'Where to Play',
        'Kluby' => 'Clubs',
        'Herny' => 'Venues',
        'Soutěže' => 'Competitions',
        'Jak začít' => 'Getting Started',
        'Jsem začátečník' => "I'm a Beginner",
        'Jsem rekreační hráč' => "I'm a Recreational Player",
        'Jsem rodič' => "I'm a Parent",
        'Pravidla kulečníku' => 'Billiards Rules',
        'Svaz' => 'Federation',
        'Český pool — domů' => 'Czech Pool — Home',
        'Hlavní navigace' => 'Main navigation',
        'Registrace na turnaje' => 'Tournament Registration',
        'Hledat' => 'Search',
        'Otevřít menu' => 'Open menu',
        'Zavřít vyhledávání' => 'Close search',
        'Hledat kluby, hráče, novinky…' => 'Search clubs, players, news…',
        'Přepnout jazyk' => 'Switch language',
        'Centrální platforma Českého poolbilliardu, sportovní sekce, která je součástí Českomoravského billiardového svazu.' => 'The central platform of Czech Pool, the billiards section of the Czech-Moravian Billiards Federation.',
        'Odkazy v patičce' => 'Footer links',
        'Hraj' => 'Play',
        'Začni' => 'Get Started',
        'Najdi si klub' => 'Find a Club',
        'Najdi si hernu' => 'Find a Venue',
        'Časté dotazy' => 'FAQ',
        'Sportovní svaz' => 'Sports Federation',
        'Výkonný výbor' => 'Executive Committee',
        'Partneři' => 'Partners',
        'Český poolbilliard' => 'Czech Pool Billiards',
        'Vytvořeno s ❤ pro českou poolovou komunitu' => 'Made with ❤ for the Czech pool community',

        // Page titles/descriptions
        'Poolbilliard — Český svaz poolbilliardu' => 'Pool Billiards — Czech Pool Federation',
        'Oficiální web Českého svazu poolbilliardu — turnaje, žebříčky, kluby, herny a vše, co potřebujete k začátku s poolbilliardem.' => 'Official website of the Czech Pool Federation — tournaments, rankings, clubs, venues and everything you need to get started with pool.',
        'Odpovědi na nejčastější dotazy o poolbilliardu, registraci, soutěžích a členství v Českém svazu poolbilliardu.' => 'Answers to the most common questions about pool, registration, competitions and membership in the Czech Pool Federation.',
        'Partneři a sponzoři' => 'Partners and Sponsors',
        'Partneři a sponzoři Českého svazu poolbilliardu, kteří podporují rozvoj poolbilliardu v Česku.' => 'Partners and sponsors of the Czech Pool Federation who support the development of pool in Czechia.',
        'Aktuality a novinky ze světa Českého poolbilliardu — výsledky turnajů, reportáže a dění ve svazu.' => 'News and updates from the world of Czech Pool — tournament results, reports and federation happenings.',
        'Přehled klubů Českého svazu poolbilliardu — najděte klub ve svém okolí a připojte se k hráčské komunitě.' => 'Overview of Czech Pool Federation clubs — find a club near you and join the player community.',
        'Katalog kulečníkových heren v Česku — najděte hernu ve svém okolí, otevírací dobu i nabízené sporty.' => 'Directory of pool venues in Czechia — find a venue nearby, its opening hours and sports offered.',
        'Kalendář turnajů a pravidelných akcí Českého svazu poolbilliardu — přehled podle měsíců.' => 'Calendar of tournaments and recurring events of the Czech Pool Federation — organized by month.',
        'Přehled soutěží a žebříčků Českého svazu poolbilliardu.' => 'Overview of competitions and rankings of the Czech Pool Federation.',
        'Pravidla poolbilliardu, vyvrácené mýty a přehled jednotlivých disciplín podle Českého svazu poolbilliardu.' => 'Pool rules, debunked myths and an overview of the disciplines according to the Czech Pool Federation.',
        'Výkonný výbor, dokumenty a organizační struktura Českého svazu poolbilliardu.' => 'Executive committee, documents and organizational structure of the Czech Pool Federation.',
        'Zprávy a oznámení výkonného výboru Českého svazu poolbilliardu.' => 'News and announcements from the executive committee of the Czech Pool Federation.',
        'Zaregistrujte svou kulečníkovou hernu zdarma do katalogu Českého poolbilliardu.' => 'Register your pool venue for free in the Czech Pool directory.',
        'Hledaná stránka neexistuje nebo byla přesunuta.' => "The page you're looking for doesn't exist or has been moved.",
        'klub Českého poolbilliardu.' => 'club of the Czech Pool Federation.',
        'herna v katalogu Českého poolbilliardu.' => 'venue in the Czech Pool directory.',
        'turnaj v kalendáři Českého poolbilliardu.' => 'tournament in the Czech Pool calendar.',
        'pravidelný turnaj v kalendáři Českého poolbilliardu.' => 'recurring tournament in the Czech Pool calendar.',

        // Errors
        'Stránka nenalezena' => 'Page Not Found',
        'Stránka nenalezena (404)' => 'Page Not Found (404)',
        'Zkontrolujte prosím adresu v prohlížeči, nebo se vraťte na hlavní stránku.' => 'Please check the address in your browser, or return to the homepage.',
        'Zpět na hlavní stránku' => 'Back to homepage',

        // Kluby / Herny
        'Kluby po celé České republice' => 'Clubs across Czechia',
        'Kulečníkové kluby' => 'Pool Clubs',
        'Kulečníkové herny' => 'Pool Venues',
        'Mapa klubů v České republice' => 'Map of clubs in Czechia',
        'Mapa heren v České republice' => 'Map of venues in Czechia',
        'Seznam klubů' => 'List of clubs',
        'Kluby v regionu :region' => 'Clubs in the :region region',
        'Název klubu' => 'Club name',
        'Detail' => 'Detail',
        'O klubu' => 'About the Club',
        'Členové klubu' => 'Club Members',
        'Nábor otevřen' => 'Recruitment Open',
        'Nábor uzavřen' => 'Recruitment Closed',
        'Ambasador klubu' => 'Club Ambassador',
        'Web klubu' => 'Club website',
        'Kde nás najdeš' => 'Where to find us',
        'Zpět na kluby' => 'Back to clubs',
        'Zpět na herny' => 'Back to venues',
        'O herně' => 'About the Venue',
        'Nabízené sporty' => 'Sports Offered',
        'Fotogalerie' => 'Photo Gallery',
        'Otevírací doba' => 'Opening Hours',
        'Kontakt' => 'Contact',
        'Web herny' => 'Venue website',
        'Web' => 'Website',
        'Vyhledat hernu' => 'Search venue',
        'Najít hernu' => 'Find a Venue',
        'Najít klub' => 'Find a Club',
        'Najít turnaj' => 'Find a Tournament',
        'Navigovat' => 'Navigate',
        'Mapa – :place' => 'Map — :place',
        'Lokalita' => 'Location',
        'Město nebo region' => 'City or region',
        'Hledat podle názvu, města, adresy…' => 'Search by name, city, address…',
        'Zaregistrovat nový klub' => 'Register a new club',

        // Registrace herny (form)
        'Registrace herny' => 'Venue Registration',
        'Přidejte svou hernu do katalogu' => 'Add your venue to the directory',
        'Provozujete kulečníkovou hernu? Zaregistrujte ji zdarma do adresáře Český Poolbilliard. Po schválení se objeví v seznamu heren a na interaktivní mapě, kde si vás najdou hráči z vašeho okolí.' => 'Do you run a pool venue? Register it for free in the Czech Pool directory. Once approved, it will appear in the venue list and on the interactive map, where nearby players will find you.',
        'Děkujeme za registraci' => 'Thank you for registering',
        'Vaši hernu jsme přijali ke schválení. Ozveme se, jakmile ji ověříme a zařadíme do katalogu.' => "We've received your venue for approval. We'll be in touch once we verify it and add it to the directory.",
        'Jak to funguje' => 'How it works',
        'Vyplňte pole níže — čím kompletnější údaje, tím rychleji hernu schválíme. Povinné údaje jsou označené hvězdičkou. Poloha herny se používá pro zobrazení na mapě; pokud GPS souřadnice neznáte, vyplňte je nulou a doplníme je při schvalování.' => "Fill in the fields below — the more complete the details, the faster we can approve your venue. Required fields are marked with an asterisk. The venue's location is used to display it on the map; if you don't know the GPS coordinates, enter zero and we'll fill them in during approval.",
        'Nechte prázdné' => 'Leave blank',
        'Základní údaje' => 'Basic Information',
        'Název herny *' => 'Venue name *',
        'Např. Harlequin Pool Club' => 'E.g. Harlequin Pool Club',
        'Popis herny *' => 'Venue description *',
        'Krátký popis herny, vybavení, atmosféry, počet stolů...' => 'Short description of the venue, equipment, atmosphere, number of tables...',
        'Adresa a poloha' => 'Address and location',
        'Ulice a číslo popisné *' => 'Street and house number *',
        'Město *' => 'City *',
        'Město' => 'City',
        'Kraj *' => 'Region *',
        'Kraj' => 'Region',
        'Vyberte kraj...' => 'Select a region...',
        'Zeměpisná šířka (lat)' => 'Latitude (lat)',
        'Zeměpisná délka (lng)' => 'Longitude (lng)',
        'Nabízené sporty *' => 'Sports Offered *',
        'Otevírací doba (nepovinné)' => 'Opening hours (optional)',
        'např. 14:00–24:00 nebo Zavřeno' => 'e.g. 14:00–24:00 or Closed',
        'Kontakt (nepovinné)' => 'Contact (optional)',
        'Telefon' => 'Phone',
        'Telefon (volitelné)' => 'Phone (optional)',
        'E-mail' => 'Email',
        'Odeslat ke schválení' => 'Submit for approval',
        'Zpět na seznam heren' => 'Back to venue list',

        // Novinky / Zpravodajství
        'Další novinky' => 'More news',
        'Další články' => 'More articles',
        'Důležité zprávy' => 'Important News',
        'DŮLEŽITÉ' => 'IMPORTANT',
        'Důležité' => 'Important',
        'Zprávy výboru' => 'Committee News',
        'Zpět na novinky' => 'Back to news',
        'Zpět na zprávy výboru' => 'Back to committee news',
        'Všechny zprávy VV' => 'All executive committee news',
        'Archiv všech zpráv' => 'Archive of all news',
        'Kontakty na výkonný výbor' => 'Executive committee contacts',
        'Pro jakékoliv informace od Sportovního svazu kontaktujte výkonný výbor na emailu :link' => 'For any information from the Sports Federation, contact the executive committee at :link',
        'Hledej ve zprávách' => 'Search news',

        // Kalendář / turnaje / soutěže
        'Dnes' => 'Today',
        'Předchozí měsíc' => 'Previous month',
        'Následující měsíc' => 'Next month',
        'Filtrovat podle kategorie' => 'Filter by category',
        'Filtrovat podle typu akce' => 'Filter by event type',
        'Zdrojové kalendáře' => 'Source calendars',
        'Žádné akce neodpovídají zvoleným filtrům.' => 'No events match the selected filters.',
        'Kompletní kalendář' => 'Complete calendar',
        'Přímé přenosy z turnajů sledujte na :link' => 'Watch tournament live streams on :link',
        'Zpět na kalendář' => 'Back to calendar',
        'Přihlášky a detail turnaje' => 'Registration and tournament details',
        'Amatérský turnaj' => 'Amateur Tournament',
        'Amatérské turnaje' => 'Amateur Tournaments',
        'Odkazy' => 'Links',
        'Detail herny — :name' => 'Venue detail — :name',
        'Kontaktovat organizátora' => 'Contact organizer',
        'Termín bude upřesněn' => 'Date to be announced',
        'Kalendář soutěží' => 'Competition Calendar',
        'Systémy soutěží' => 'Competition Systems',
        'Žebříčky' => 'Rankings',
        'Aktuální pořadí ve všech sériích a kategoriích — TOP 10 hráčů.' => 'Current standings across all series and categories — top 10 players.',
        'Celý žebříček' => 'Full Ranking',
        'Detail série' => 'Series detail',
        'Zahraniční' => 'International',
        'Klub' => 'Club',

        // Jak začít
        'Kde začít' => 'Where to Start',
        'Která situace vás nejlépe vystihuje?' => 'Which situation best describes you?',
        'Chci se zlepšit' => 'I want to improve',
        'Chcete začít hrát poolbilliard? Zjistěte, jak najít klub, přihlásit se k prvnímu turnaji a zorientovat se v soutěžích.' => 'Want to start playing pool? Find out how to join a club, enter your first tournament and get oriented in competitions.',
        'Vyber si klub ve svém okolí a udělej první krok do světa závodního poolbilliardu.' => 'Choose a club near you and take your first step into the world of competitive pool.',
        'Vyzkoušej si sportovní atmosféru a šanci uhrát výsledek, i jako začátečník.' => 'Experience the competitive atmosphere and a chance to score a result, even as a beginner.',
        'Přátelská komunita všech úrovní' => 'A friendly community for all levels',
        'Tréninky, ligy i turnaje pro každého' => 'Training, leagues and tournaments for everyone',
        'Chceš si zahrát?' => 'Want to play?',

        // FAQ / Pravidla
        'Otázky a odpovědi' => 'Questions and Answers',
        'Nenašli jste odpověď?' => "Didn't find your answer?",
        'Napište nám a rádi pomůžeme — na e-maily odpovídáme obvykle do 2 pracovních dnů.' => "Write to us and we'll be happy to help — we usually reply to emails within 2 business days.",
        'Napsat e-mail' => 'Write an email',
        'Zobrazit pravidla' => 'View rules',
        'Mýtus' => 'Myth',
        'Správně' => 'Correct',
        'Stanovy ČMBS' => 'ČMBS Statutes',
        'Oficiální web ČMBS' => 'Official ČMBS website',

        // Formuláře / kontakt / newsletter
        'Popište, co byste chtěli — trénink, klub, kroužek pro dítě, apod.' => "Describe what you'd like — training, a club, a group for a child, etc.",
        'Odeslat poptávku' => 'Send inquiry',
        'Vaše jméno' => 'Your name',
        'Jméno' => 'Name',
        'Co hledáte?' => 'What are you looking for?',
        'Newsletter' => 'Newsletter',
        'Nenech si ujít žádnou novinku. Přihlas se k odběru newsletteru a dostávej přehled turnajů, výsledků a zpráv ze světa českého poolbilliardu.' => "Don't miss any news. Subscribe to our newsletter and get an overview of tournaments, results and news from the world of Czech pool.",
        'Vložte svůj e-mail' => 'Enter your email',
        'Odebírat' => 'Subscribe',
        'Přihlášením k odběru vyjadřujete' => 'By subscribing you express',
        'souhlas se zpracováním osobních údajů' => 'consent to personal data processing',

        // JSON-LD / structured data
        'Český svaz poolbilliardu' => 'Czech Pool Federation',
        'Česká republika' => 'Czech Republic',

        // Misc
        'Vše' => 'All',
        'Rok dokumentů' => 'Document year',
        'Rozbalit podnabídku :text' => 'Expand submenu :text',
        'Rychlá navigace na sekce stránky' => 'Quick navigation to page sections',
        'Stránkování' => 'Pagination',
        'Předchozí stránka' => 'Previous page',
        'Další stránka' => 'Next page',
        'Zpět' => 'Back',
        'Více' => 'More',
    ];

    public function run(): void
    {
        foreach (self::LINES as $cs => $en) {
            LanguageLine::updateOrCreate(
                ['group' => '*', 'key' => $cs],
                ['text' => ['en' => $en]]
            );
        }
    }
}
