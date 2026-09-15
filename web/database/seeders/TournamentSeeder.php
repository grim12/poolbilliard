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
            'url' => '/turnaj/',
            'category' => 'MEZINÁRODNÍ',
            'start_date' => '2026-08-20',
            'end_date' => '2026-08-23',
            'location_text' => 'Itálie · Treviso',
            'badge' => false,
        ],
        [
            'title' => 'Mistrovství ČR 9-ball',
            'url' => '/turnaj/',
            'category' => 'ČMBS',
            'start_date' => '2026-09-12',
            'end_date' => '2026-09-13',
            'location_text' => 'Praha · BC Řipská',
            'badge' => true,
        ],
        [
            'title' => 'MR Dvojic',
            'url' => '/turnaj/',
            'category' => 'ČMBS',
            'start_date' => '2026-09-19',
            'end_date' => null,
            'location_text' => 'Praha · Rajská Zahrada',
            'badge' => false,
        ],
        [
            'title' => 'MR Smíšených Dvojic',
            'url' => '/turnaj/',
            'category' => 'ČMBS',
            'start_date' => '2026-09-20',
            'end_date' => null,
            'location_text' => 'Praha · Rajská Zahrada',
            'badge' => false,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::TOURNAMENTS as $index => $tournament) {
            $categoryName = $tournament['category'];
            unset($tournament['category']);

            Tournament::updateOrCreateByTranslation(
                'title',
                $tournament['title'],
                [
                    ...$tournament,
                    'tournament_category_id' => TournamentCategory::whereJsonContainsLocale('name', 'cs', $categoryName)->value('id'),
                    'sort_order' => $index,
                ]
            );
        }
    }
}
