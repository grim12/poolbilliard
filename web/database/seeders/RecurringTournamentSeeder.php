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
            'title_en' => 'Tournaments at Balabuška',
            'frequency' => 'Každá středa',
            'frequency_en' => 'Every Wednesday',
            'location_text' => 'Praha',
            'location_text_en' => 'Prague',
            'herna' => 'Billiard Club Balabuška Bohdalec',
        ],
        [
            'title' => 'Turnaje v Harlequinu',
            'title_en' => 'Tournaments at Harlequin',
            'frequency' => 'Každá neděle',
            'frequency_en' => 'Every Sunday',
            'location_text' => 'Praha',
            'location_text_en' => 'Prague',
            'herna' => 'Billiard Club Harlequin Praha',
        ],
        [
            'title' => 'Turnaje v Maple Pool Club',
            'title_en' => 'Tournaments at Maple Pool Club',
            'frequency' => 'Každý čtvrtek',
            'frequency_en' => 'Every Thursday',
            'location_text' => 'Pardubice',
            'location_text_en' => 'Pardubice',
            'herna' => null,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::TOURNAMENTS as $index => $tournament) {
            $title = $tournament['title'];
            $hernaName = $tournament['herna'];
            unset($tournament['herna']);

            $tournament['title'] = ['cs' => $tournament['title'], 'en' => $tournament['title_en']];
            $tournament['frequency'] = ['cs' => $tournament['frequency'], 'en' => $tournament['frequency_en']];
            $tournament['location_text'] = ['cs' => $tournament['location_text'], 'en' => $tournament['location_text_en']];
            unset($tournament['title_en'], $tournament['frequency_en'], $tournament['location_text_en']);

            RecurringTournament::updateOrCreateByTranslation(
                'title',
                $title,
                [
                    ...$tournament,
                    'herna_id' => $hernaName ? Herna::where('name', $hernaName)->value('id') : null,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
