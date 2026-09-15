<?php

namespace Database\Seeders;

use App\Models\TournamentCategory;
use Illuminate\Database\Seeder;

class TournamentCategorySeeder extends Seeder
{
    /**
     * Mirrors the tagText/tagColor values used across ui/src/_data/turnaje.json.
     */
    private const CATEGORIES = [
        ['name' => 'ČMBS', 'color' => 'primary'],
        ['name' => 'MEZINÁRODNÍ', 'color' => 'gold'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::CATEGORIES as $index => $category) {
            TournamentCategory::updateOrCreateByTranslation(
                'name',
                $category['name'],
                ['color' => $category['color'], 'sort_order' => $index]
            );
        }
    }
}
