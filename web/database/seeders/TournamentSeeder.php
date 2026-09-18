<?php

namespace Database\Seeders;

use App\Models\Tournament;
use App\Models\TournamentCategory;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    /**
     * Mirrors ui/src/_data/turnaje.json. `category` is a name lookup against
     * TournamentCategorySeeder's rows (run before this one, see DatabaseSeeder), resolved to
     * tournament_category_id below — not stored directly.
     */
    private const TOURNAMENTS = [
        [
            'title' => 'EuroTour 10-ball',
            'title_en' => 'EuroTour 10-ball',
            'url' => 'https://eurotour.info/',
            'category' => 'MEZINÁRODNÍ',
            'start_date' => '2026-08-20',
            'end_date' => '2026-08-23',
            'location_text' => 'Itálie · Treviso',
            'location_text_en' => 'Italy · Treviso',
            'description' => '<p>Zastávka mezinárodního EuroTour seriálu v poolbilliardu, otevřená hráčům ze všech zemí.</p><ul><li>Disciplína: 10-ball</li><li>Systém hry: DBKO</li><li>Startovné a přihlášky přes oficiální stránky turnaje</li></ul>',
            'description_en' => '<p>A stop on the international EuroTour pool billiards series, open to players from every country.</p><ul><li>Discipline: 10-ball</li><li>Format: double elimination</li><li>Entry fee and registration via the tournament\'s official website</li></ul>',
            'badge' => false,
        ],
        [
            'title' => 'Mistrovství ČR 9-ball',
            'title_en' => 'Czech 9-ball Championship',
            'url' => 'https://vysledky.cmbs.cz/turnaje/mcr-9-ball',
            'category' => 'ČMBS',
            'start_date' => '2026-09-12',
            'end_date' => '2026-09-13',
            'location_text' => 'Praha · BC Řipská',
            'location_text_en' => 'Prague · BC Řipská',
            'description' => '<p>Mistrovství České republiky jednotlivců v 9-ball. Uzavřený turnaj pro 32 hráčů – TOP 16 z celoročního žebříčku a postupující z kvalifikace.</p><ul><li>Disciplína: 9-ball</li><li>Systém hry: DBKO 32</li><li>Prezence: 10:00 – 10:20</li><li>Startovné: 500 Kč; junioři a ženy 300 Kč</li></ul>',
            'description_en' => '<p>The Czech Individual Championship in 9-ball. A closed tournament for 32 players — the top 16 from the annual ranking plus qualifiers.</p><ul><li>Discipline: 9-ball</li><li>Format: 32-player double elimination</li><li>Check-in: 10:00 – 10:20</li><li>Entry fee: 500 CZK; juniors and women 300 CZK</li></ul>',
            'badge' => true,
        ],
        [
            'title' => 'MR Dvojic',
            'title_en' => 'Czech Doubles Championship',
            'url' => 'https://vysledky.cmbs.cz/turnaje/mr-dvojic',
            'category' => 'ČMBS',
            'start_date' => '2026-09-19',
            'end_date' => null,
            'location_text' => 'Praha · Rajská Zahrada',
            'location_text_en' => 'Prague · Rajská zahrada',
            'description' => '<p>Mistrovství republiky dvojic. Otevřený turnaj pro páry hráčů registrovaných v ČMBS.</p><ul><li>Disciplína: 8-ball</li><li>Systém hry: skupiny + play-off</li><li>Startovné: 300 Kč / dvojice</li></ul>',
            'description_en' => '<p>The Czech Doubles Championship. An open tournament for pairs of ČMBS-registered players.</p><ul><li>Discipline: 8-ball</li><li>Format: groups + playoffs</li><li>Entry fee: 300 CZK per pair</li></ul>',
            'badge' => false,
        ],
        [
            'title' => 'MR Smíšených Dvojic',
            'title_en' => 'Czech Mixed Doubles Championship',
            'url' => 'https://vysledky.cmbs.cz/turnaje/mr-smisenych-dvojic',
            'category' => 'ČMBS',
            'start_date' => '2026-09-20',
            'end_date' => null,
            'location_text' => 'Praha · Rajská Zahrada',
            'location_text_en' => 'Prague · Rajská zahrada',
            'description' => '<p>Mistrovství republiky smíšených dvojic (muž + žena). Otevřený turnaj pro páry hráčů registrovaných v ČMBS.</p><ul><li>Disciplína: 8-ball</li><li>Systém hry: skupiny + play-off</li><li>Startovné: 300 Kč / dvojice</li></ul>',
            'description_en' => '<p>The Czech Mixed Doubles Championship (one man, one woman). An open tournament for pairs of ČMBS-registered players.</p><ul><li>Discipline: 8-ball</li><li>Format: groups + playoffs</li><li>Entry fee: 300 CZK per pair</li></ul>',
            'badge' => false,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::TOURNAMENTS as $index => $tournament) {
            $title = $tournament['title'];
            $categoryName = $tournament['category'];
            unset($tournament['category']);

            $tournament['title'] = ['cs' => $tournament['title'], 'en' => $tournament['title_en']];
            $tournament['location_text'] = ['cs' => $tournament['location_text'], 'en' => $tournament['location_text_en']];
            $tournament['description'] = ['cs' => $tournament['description'], 'en' => $tournament['description_en']];
            unset($tournament['title_en'], $tournament['location_text_en'], $tournament['description_en']);

            Tournament::updateOrCreateByTranslation(
                'title',
                $title,
                [
                    ...$tournament,
                    'tournament_category_id' => TournamentCategory::whereJsonContainsLocale('name', 'cs', $categoryName)->value('id'),
                    'sort_order' => $index,
                ]
            );
        }
    }
}
