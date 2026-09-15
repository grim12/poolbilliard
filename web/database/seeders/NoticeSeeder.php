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
        ['title' => 'Poslední 3 dny na registraci na MČR 9 ball', 'is_important' => true, 'published_at' => '2026-06-17', 'excerpt' => 'Připomínáme, že registrace na Mistrovství České republiky v disciplíně 9-Ball se uzavírá v pátek 20. 6. 2026 ve 23:59. Přihlášku je možné podat prostřednictvím přihlašovacího systému ČMBS na poolbilliard.cz. Startovné je splatné při registraci, bez uhrazené platby není přihláška platná. Výkonný výbor upozorňuje hráče, že kapacita turnaje je omezená…'],
        ['title' => 'Zveřejnili jsme nasazení hráčů do sobotního 4. kola ČPT v Praze', 'is_important' => false, 'published_at' => '2026-06-16', 'excerpt' => 'Na webu výsledkového servisu cmbs.cz je k dispozici nasazení hráčů pro sobotní 4. kolo České Poolové Tour, které se odehraje v Billiard Rajské zahradě v Praze. Hráči jsou nasazeni podle aktuálního žebříčku ČPT po třech odehraných kolech. Prezence účastníků proběhne od 9:00, samotná hra začne v 10:00. Případné odhlášky je nutné nahlásit nejpozd…'],
        ['title' => 'Pozvánka na valnou hromadu konanou v neděli 21. 6. 2026', 'is_important' => true, 'published_at' => '2026-06-15', 'excerpt' => 'Výkonný výbor svolává řádnou valnou hromadu sekce Pool ČMBS, která se uskuteční v neděli 21. 6. 2026 od 10:00 v hotelu Olšanka v Praze. Program valné hromady: 1. Zahájení a volba orgánů valné hromady 2. Zpráva o činnosti výkonného výboru za rok 2025 3. Zpráva o hospodaření a zpráva revizní komise 4. Schválení plánu činnosti a rozpočtu…'],
        ['title' => 'Změna propozic pro letní sérii amatérských turnajů', 'is_important' => false, 'published_at' => '2026-06-14', 'excerpt' => 'Výkonný výbor schválil úpravu propozic pro letní sérii amatérských turnajů, která startuje v červenci. Hlavní změny oproti loňskému ročníku: - Disciplína 8-Ball se hraje na rasu 5, předtím 4 - Maximální handicap mezi soupeři snížen na 2 body - Startovné jednotně 200 Kč ve všech kolech série - Zavedení nové kategorie „Začátečníci“ s vlastním pavoukem…'],
        ['title' => 'Termínový kalendář soutěží pro sezónu 2026 schválen', 'is_important' => true, 'published_at' => '2025-11-15', 'excerpt' => null],
        ['title' => 'Změna registračního řádu: nové podmínky přestupů hráčů', 'is_important' => false, 'published_at' => '2026-07-15', 'excerpt' => null],
        ['title' => 'Zápis z jednání VV ze dne 30. června 2026', 'is_important' => false, 'published_at' => '2026-07-02', 'excerpt' => null],
        ['title' => 'Aktualizovaný sazebník startovného pro rok 2026', 'is_important' => false, 'published_at' => '2026-01-20', 'excerpt' => null],
    ];

    private const MCR_9BALL_BODY = <<<'HTML'
        <p>Připomínáme, že registrace na Mistrovství České republiky v disciplíně 9-Ball se uzavírá v pátek 20. 6. 2026 ve 23:59.</p>
        <p>Přihlášku je možné podat prostřednictvím přihlašovacího systému ČMBS na poolbilliard.cz. Startovné je splatné při registraci, bez uhrazené platby není přihláška platná.</p>
        <p>Výkonný výbor upozorňuje hráče, že kapacita turnaje je omezená a o pořadí na startovní listině rozhoduje čas přijetí platby. Pozdější přihlášky budou zařazeny pouze v případě volných míst.</p>
        HTML;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::NOTICES as $data) {
            $extra = $data['title'] === 'Poslední 3 dny na registraci na MČR 9 ball'
                ? ['body' => self::MCR_9BALL_BODY]
                : [];

            Notice::updateOrCreateByTranslation(
                'title',
                $data['title'],
                [...$data, ...$extra]
            );
        }
    }
}
