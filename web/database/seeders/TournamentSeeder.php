<?php

namespace Database\Seeders;

use App\Models\Tournament;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    /**
     * Mirrors ui/src/_data/turnaje.json.
     */
    private const TOURNAMENTS = [
        [
            'title' => 'EuroTour 10-ball',
            'url' => '/turnaj/',
            'tag_text' => 'MEZINÁRODNÍ',
            'tag_color' => 'gold',
            'date_text' => '20. – 23. srpna 2026',
            'start_date' => '2026-08-20',
            'location_text' => 'Itálie · Treviso',
            'badge' => false,
        ],
        [
            'title' => 'Mistrovství ČR 9-ball',
            'url' => '/turnaj/',
            'tag_text' => 'ČMBS',
            'tag_color' => 'primary',
            'date_text' => '12. – 13. září 2026',
            'start_date' => '2026-09-12',
            'location_text' => 'Praha · BC Řipská',
            'badge' => true,
        ],
        [
            'title' => 'MR Dvojic',
            'url' => '/turnaj/',
            'tag_text' => 'ČMBS',
            'tag_color' => 'primary',
            'date_text' => '19. září 2026',
            'start_date' => '2026-09-19',
            'location_text' => 'Praha · Rajská Zahrada',
            'badge' => false,
        ],
        [
            'title' => 'MR Smíšených Dvojic',
            'url' => '/turnaj/',
            'tag_text' => 'ČMBS',
            'tag_color' => 'primary',
            'date_text' => '12. – 13. září 2026',
            'start_date' => '2026-09-12',
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
            Tournament::updateOrCreate(
                ['title' => $tournament['title']],
                [...$tournament, 'sort_order' => $index]
            );
        }
    }
}
