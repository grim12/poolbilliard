<?php

namespace Database\Seeders;

use App\Models\Leaderboard;
use Illuminate\Database\Seeder;

class LeaderboardSeeder extends Seeder
{
    /**
     * Mirrors ui/src/_data/souteze.json (10 players per series — the superset of
     * _data/zebricky.json's 5, which was always just this same data truncated for the
     * homepage). `entries` order is the ranking itself, no separate rank field.
     */
    private const LEADERBOARDS = [
        [
            'title' => 'Celostátní roční žebříček',
            'featured' => true,
            'entries' => [
                ['name' => 'Roman Hybler', 'club' => 'MPC Pardubice'],
                ['name' => 'Petr Urban', 'club' => 'MPC Pardubice'],
                ['name' => 'Mariusz Skoneczny', 'club' => 'MPC Pardubice'],
                ['name' => 'Jan Meisner', 'club' => 'SK Harlequin Praha'],
                ['name' => 'Matouš Vlk', 'club' => 'MPC Pardubice'],
                ['name' => 'David Žalman', 'club' => 'MPC Pardubice'],
                ['name' => 'Martin Forman', 'club' => 'SC Rozmarýn Brno'],
                ['name' => 'Mykola Moroz', 'club' => 'Řipská BC'],
                ['name' => 'Jaromír Linha', 'club' => 'SK Harlequin Praha'],
                ['name' => 'Tomáš Vančura', 'club' => 'BC Metropol České Budějovice'],
            ],
            'sort_order' => 0,
        ],
        [
            'title' => 'Česká Poolová Tour 2026',
            'featured' => false,
            'entries' => [
                ['name' => 'Petr Urban', 'club' => 'MPC Pardubice'],
                ['name' => 'Mykola Moroz', 'club' => 'Řipská BC'],
                ['name' => 'Jan Meisner', 'club' => 'SK Harlequin Praha'],
                ['name' => 'Matouš Vlk', 'club' => 'MPC Pardubice'],
                ['name' => 'David Žalman', 'club' => 'MPC Pardubice'],
                ['name' => 'Roman Hybler', 'club' => 'MPC Pardubice'],
                ['name' => 'Alexandr Hofmann', 'club' => 'Řipská BC'],
                ['name' => 'Christos Seizis', 'club' => 'BC Balabuška'],
                ['name' => 'Jaroslav Tichý', 'club' => 'Řipská BC'],
                ['name' => 'Marek Hajdovský', 'club' => 'DELTA Billiard'],
            ],
            'sort_order' => 1,
        ],
        [
            'title' => 'MČR Juniorů 2026',
            'featured' => false,
            'entries' => [
                ['name' => 'Alexandr Hofmann', 'club' => 'Řipská BC'],
                ['name' => 'Dominik Demel', 'club' => 'BC Ostrava'],
                ['name' => 'Václav Skokan', 'club' => 'MPC Pardubice'],
                ['name' => 'Robert Holub', 'club' => 'MPC Pardubice'],
                ['name' => 'Adam Výskot', 'club' => 'MPC Pardubice'],
                ['name' => 'Matyáš Říha', 'club' => 'MPC Pardubice'],
                ['name' => 'David Švéda', 'club' => 'MPC Pardubice'],
                ['name' => 'Jakub Novák', 'club' => 'Řipská BC'],
                ['name' => 'Tomáš Beneš', 'club' => 'BC Ostrava'],
                ['name' => 'Filip Krejčí', 'club' => 'SK Harlequin Praha'],
            ],
            'sort_order' => 2,
        ],
        [
            'title' => 'MČR Veteránů 2026',
            'featured' => false,
            'entries' => [
                ['name' => 'Christos Seizis', 'club' => 'BC Balabuška'],
                ['name' => 'Pavel Halamka', 'club' => 'SK Harlequin Praha'],
                ['name' => 'Tomáš Vančura', 'club' => 'BC Metropol České Budějovice'],
                ['name' => 'Jan Kotěra', 'club' => 'DUNS Benátky n. J.'],
                ['name' => 'Jaromír Linha', 'club' => 'SK Harlequin Praha'],
                ['name' => 'Petr Dvořák', 'club' => 'BC Kladno'],
                ['name' => 'Miloš Svoboda', 'club' => '1. KK Chomutov'],
                ['name' => 'Zdeněk Procházka', 'club' => 'BC Louny'],
                ['name' => 'Karel Novotný', 'club' => 'BC Přichovice'],
                ['name' => 'Josef Pokorný', 'club' => 'SK Petřiny'],
            ],
            'sort_order' => 3,
        ],
        [
            'title' => 'ČMBS Junior Open 2026',
            'featured' => false,
            'entries' => [
                ['name' => 'Alexandr Hofmann', 'club' => 'Řipská BC'],
                ['name' => 'Václav Skokan', 'club' => 'MPC Pardubice'],
                ['name' => 'Adam Výskot', 'club' => 'MPC Pardubice'],
                ['name' => 'Matyáš Říha', 'club' => 'MPC Pardubice'],
                ['name' => 'David Švéda', 'club' => 'MPC Pardubice'],
                ['name' => 'Dominik Demel', 'club' => 'BC Ostrava'],
                ['name' => 'Robert Holub', 'club' => 'MPC Pardubice'],
                ['name' => 'Jan Malý', 'club' => 'Řipská BC'],
                ['name' => 'Ondřej Král', 'club' => 'SK Harlequin Praha'],
                ['name' => 'Vojtěch Sedláček', 'club' => 'BC Kladno'],
            ],
            'sort_order' => 4,
        ],
        [
            'title' => 'Extraliga Týmů 2026',
            'featured' => false,
            'entries' => [
                ['name' => 'BC Řipská Praha A', 'club' => null],
                ['name' => 'MPC Pardubice A', 'club' => null],
                ['name' => 'BC Řipská Praha B', 'club' => null],
                ['name' => 'Delta Billiard Brno A', 'club' => null],
                ['name' => 'SK Harlequin Praha A', 'club' => null],
                ['name' => 'BC Kladno A', 'club' => null],
                ['name' => 'BC Ostrava A', 'club' => null],
                ['name' => 'MPC Pardubice B', 'club' => null],
                ['name' => 'Řipská BC C', 'club' => null],
                ['name' => 'SK Petřiny A', 'club' => null],
            ],
            'sort_order' => 5,
        ],
        [
            'title' => 'Mistrovství ČR 2025',
            'featured' => false,
            'entries' => [
                ['name' => 'Martin Forman', 'club' => 'SC Rozmarýn Brno'],
                ['name' => 'Jan Meisner', 'club' => 'SK Harlequin Praha'],
                ['name' => 'Petr Urban', 'club' => 'MPC Pardubice'],
                ['name' => 'Marek Hajdovský', 'club' => 'DELTA Billiard'],
                ['name' => 'Jaroslav Tichý', 'club' => 'Řipská BC'],
                ['name' => 'Roman Hybler', 'club' => 'MPC Pardubice'],
                ['name' => 'Mariusz Skoneczny', 'club' => 'MPC Pardubice'],
                ['name' => 'David Žalman', 'club' => 'MPC Pardubice'],
                ['name' => 'Christos Seizis', 'club' => 'BC Balabuška'],
                ['name' => 'Mykola Moroz', 'club' => 'Řipská BC'],
            ],
            'sort_order' => 6,
        ],
        [
            'title' => 'MČR Žen 2025',
            'featured' => false,
            'entries' => [
                ['name' => 'Veronika Hubrtová', 'club' => 'MPC Pardubice'],
                ['name' => 'Kateřina Zárubová', 'club' => '1. KK Chomutov'],
                ['name' => 'Ilona Žalmanová', 'club' => 'MPC Pardubice'],
                ['name' => 'Lucie Rendová', 'club' => 'DELTA Billiard'],
                ['name' => 'Petra Krulichová', 'club' => 'BC Balabuška'],
                ['name' => 'Markéta Nováková', 'club' => 'SK Harlequin Praha'],
                ['name' => 'Barbora Svobodová', 'club' => 'BC Kladno'],
                ['name' => 'Simona Dvořáková', 'club' => '1. KK Chomutov'],
                ['name' => 'Eva Procházková', 'club' => 'BC Ostrava'],
                ['name' => 'Hana Novotná', 'club' => 'BC Přichovice'],
            ],
            'sort_order' => 7,
        ],
    ];

    public function run(): void
    {
        foreach (self::LEADERBOARDS as $data) {
            Leaderboard::updateOrCreate(['title' => $data['title']], $data);
        }
    }
}
