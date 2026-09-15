<?php

namespace Database\Seeders;

use App\Models\Herna;
use App\Models\RecurringTournament;
use Illuminate\Database\Seeder;

class RecurringTournamentSeeder extends Seeder
{
    /**
     * Mirrors ui/kalendar.njk's hardcoded recurringTournaments() call. `herna` is a name lookup
     * against HernaSeeder's rows (run before this one, see DatabaseSeeder) — "Maple Pool Club"
     * has no matching Herna record (doesn't exist in HernaSeeder), left null on purpose to
     * demonstrate the link is genuinely optional.
     */
    private const TOURNAMENTS = [
        [
            'title' => 'Turnaje v Balabušce',
            'frequency' => 'Každá středa',
            'location_text' => 'Praha',
            'herna' => 'Billiard Club Balabuška Bohdalec',
        ],
        [
            'title' => 'Turnaje v Harlequinu',
            'frequency' => 'Každá neděle',
            'location_text' => 'Praha',
            'herna' => 'Billiard Club Harlequin Praha',
        ],
        [
            'title' => 'Turnaje v Maple Pool Club',
            'frequency' => 'Každý čtvrtek',
            'location_text' => 'Pardubice',
            'herna' => null,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::TOURNAMENTS as $index => $tournament) {
            $hernaName = $tournament['herna'];
            unset($tournament['herna']);

            RecurringTournament::updateOrCreateByTranslation(
                'title',
                $tournament['title'],
                [
                    ...$tournament,
                    'herna_id' => $hernaName ? Herna::where('name', $hernaName)->value('id') : null,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
