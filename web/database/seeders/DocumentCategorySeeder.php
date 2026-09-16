<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Mirrors the group titles used by every year in ui/src/_data/svazDokumenty.js
     * (yearGroups()) plus the evergreen "Základní dokumenty"/"Formuláře" groups.
     */
    private const CATEGORIES = [
        'Soutěžní předpisy',
        'Zápisy ze schůzí VVS',
        'Zápisy z VH sekce',
        'Hospodaření sekce',
        'Základní dokumenty',
        'Formuláře',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::CATEGORIES as $index => $name) {
            DocumentCategory::updateOrCreateByTranslation('name', $name, ['sort_order' => $index]);
        }
    }
}
