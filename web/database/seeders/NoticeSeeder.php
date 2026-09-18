<?php

namespace Database\Seeders;

use App\Models\Notice;
use Illuminate\Database\Seeder;

class NoticeSeeder extends Seeder
{
    /**
     * Mirrors the notice items across ui/src/novinky.njk's sidebar and
     * ui/src/zpravodajstvi/vykonny-vybor.njk's listing (8 distinct notices total — no
     * overlapping titles between the two). "Poslední 3 dny na registraci na MČR 9 ball"
     * additionally gets the full body from ui/'s one hardcoded vykonny-vybor-detail.njk
     * example. "DŮLEŽITÉ" tag presence in ui/ becomes the is_important boolean here.
     */
    private const NOTICES = [
        ['title' => 'Poslední 3 dny na registraci na MČR 9 ball', 'title_en' => '3 days left to register for the Czech 9-ball Championship', 'is_important' => true, 'published_at' => '2026-06-17', 'excerpt' => 'Připomínáme, že registrace na Mistrovství České republiky v disciplíně 9-Ball se uzavírá v pátek 20. 6. 2026 ve 23:59. Přihlášku je možné podat prostřednictvím přihlašovacího systému ČMBS na poolbilliard.cz. Startovné je splatné při registraci, bez uhrazené platby není přihláška platná. Výkonný výbor upozorňuje hráče, že kapacita turnaje je omezená…', 'excerpt_en' => 'A reminder that registration for the Czech Championship in 9-Ball closes on Friday, June 20, 2026 at 23:59. Entries can be submitted through the ČMBS registration system at poolbilliard.cz. The entry fee is due at registration — an entry is not valid without payment. The executive committee reminds players that the tournament has limited capacity…'],
        ['title' => 'Zveřejnili jsme nasazení hráčů do sobotního 4. kola ČPT v Praze', 'title_en' => "Player seeding for Saturday's round 4 of the Czech Pool Tour in Prague is published", 'is_important' => false, 'published_at' => '2026-06-16', 'excerpt' => 'Na webu výsledkového servisu cmbs.cz je k dispozici nasazení hráčů pro sobotní 4. kolo České Poolové Tour, které se odehraje v Billiard Rajské zahradě v Praze. Hráči jsou nasazeni podle aktuálního žebříčku ČPT po třech odehraných kolech. Prezence účastníků proběhne od 9:00, samotná hra začne v 10:00. Případné odhlášky je nutné nahlásit nejpozd…', 'excerpt_en' => "Player seeding for Saturday's round 4 of the Czech Pool Tour, held at Billiard Rajská zahrada in Prague, is available on the cmbs.cz results service. Players are seeded according to the current ČPT standings after three rounds. Check-in starts at 9:00, play begins at 10:00. Any withdrawals must be reported no later t…"],
        ['title' => 'Pozvánka na valnou hromadu konanou v neděli 21. 6. 2026', 'title_en' => 'Invitation to the general assembly on Sunday, June 21, 2026', 'is_important' => true, 'published_at' => '2026-06-15', 'excerpt' => 'Výkonný výbor svolává řádnou valnou hromadu sekce Pool ČMBS, která se uskuteční v neděli 21. 6. 2026 od 10:00 v hotelu Olšanka v Praze. Program valné hromady: 1. Zahájení a volba orgánů valné hromady 2. Zpráva o činnosti výkonného výboru za rok 2025 3. Zpráva o hospodaření a zpráva revizní komise 4. Schválení plánu činnosti a rozpočtu…', 'excerpt_en' => "The executive committee is calling the section's regular general assembly, held on Sunday, June 21, 2026 at 10:00 at Hotel Olšanka in Prague. Assembly agenda: 1. Opening and election of assembly officers 2. Report on the executive committee's 2025 activities 3. Financial report and audit committee report 4. Approval of the activity plan and budget…"],
        ['title' => 'Změna propozic pro letní sérii amatérských turnajů', 'title_en' => 'Rule changes for the summer amateur tournament series', 'is_important' => false, 'published_at' => '2026-06-14', 'excerpt' => 'Výkonný výbor schválil úpravu propozic pro letní sérii amatérských turnajů, která startuje v červenci. Hlavní změny oproti loňskému ročníku: - Disciplína 8-Ball se hraje na rasu 5, předtím 4 - Maximální handicap mezi soupeři snížen na 2 body - Startovné jednotně 200 Kč ve všech kolech série - Zavedení nové kategorie „Začátečníci“ s vlastním pavoukem…', 'excerpt_en' => 'The executive committee approved rule changes for the summer amateur tournament series starting in July. Main changes from last year: - 8-Ball is now played to a race of 5, previously 4 - Maximum handicap between opponents reduced to 2 points - Flat entry fee of 200 CZK across all rounds - Introduction of a new "Beginners" category with its own bracket…'],
        ['title' => 'Termínový kalendář soutěží pro sezónu 2026 schválen', 'title_en' => 'The 2026 season competition calendar has been approved', 'is_important' => true, 'published_at' => '2025-11-15', 'excerpt' => 'Výkonný výbor schválil termínový kalendář všech svazových soutěží pro nadcházející sezónu.', 'excerpt_en' => 'The executive committee approved the schedule for all federation competitions for the upcoming season.'],
        ['title' => 'Změna registračního řádu: nové podmínky přestupů hráčů', 'title_en' => 'Registration rules updated: new player transfer conditions', 'is_important' => false, 'published_at' => '2026-07-15', 'excerpt' => 'Výkonný výbor schválil úpravu registračního řádu upravující podmínky přestupů a hostování hráčů mezi kluby.', 'excerpt_en' => 'The executive committee approved an update to the registration rules governing player transfers and guest appearances between clubs.'],
        ['title' => 'Zápis z jednání VV ze dne 30. června 2026', 'title_en' => 'Minutes of the executive committee meeting of June 30, 2026', 'is_important' => false, 'published_at' => '2026-07-02', 'excerpt' => 'Zápis z pravidelného jednání výkonného výboru je k dispozici v sekci dokumentů.', 'excerpt_en' => 'Minutes from the regular executive committee meeting are available in the documents section.'],
        ['title' => 'Aktualizovaný sazebník startovného pro rok 2026', 'title_en' => 'Updated 2026 entry fee schedule', 'is_important' => false, 'published_at' => '2026-01-20', 'excerpt' => 'Výkonný výbor zveřejnil aktualizovaný sazebník startovného platný pro sezónu 2026.', 'excerpt_en' => 'The executive committee published the updated entry fee schedule for the 2026 season.'],
    ];

    private const MCR_9BALL_BODY = <<<'HTML'
        <p>Připomínáme, že registrace na Mistrovství České republiky v disciplíně 9-Ball se uzavírá v pátek 20. 6. 2026 ve 23:59.</p>
        <p>Přihlášku je možné podat prostřednictvím přihlašovacího systému ČMBS na poolbilliard.cz. Startovné je splatné při registraci, bez uhrazené platby není přihláška platná.</p>
        <p>Výkonný výbor upozorňuje hráče, že kapacita turnaje je omezená a o pořadí na startovní listině rozhoduje čas přijetí platby. Pozdější přihlášky budou zařazeny pouze v případě volných míst.</p>
        HTML;

    private const MCR_9BALL_BODY_EN = <<<'HTML'
        <p>A reminder that registration for the Czech Championship in 9-Ball closes on Friday, June 20, 2026 at 23:59.</p>
        <p>Entries can be submitted through the ČMBS registration system at poolbilliard.cz. The entry fee is due at registration — an entry is not valid without payment.</p>
        <p>The executive committee reminds players that the tournament has limited capacity, and the order on the start list is decided by when payment is received. Later entries will only be added if spots remain open.</p>
        HTML;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::NOTICES as $data) {
            $title = $data['title'];
            $extra = $title === 'Poslední 3 dny na registraci na MČR 9 ball'
                ? ['body' => ['cs' => self::MCR_9BALL_BODY, 'en' => self::MCR_9BALL_BODY_EN]]
                : [];

            Notice::updateOrCreateByTranslation(
                'title',
                $title,
                [
                    'title' => ['cs' => $data['title'], 'en' => $data['title_en']],
                    'excerpt' => ['cs' => $data['excerpt'], 'en' => $data['excerpt_en']],
                    'is_important' => $data['is_important'],
                    'published_at' => $data['published_at'],
                    ...$extra,
                ]
            );
        }
    }
}
