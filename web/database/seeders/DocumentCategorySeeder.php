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
        'Soutěžní předpisy' => 'Competition regulations',
        'Zápisy ze schůzí VVS' => 'Executive committee meeting minutes',
        'Zápisy z VH sekce' => 'General assembly minutes',
        'Hospodaření sekce' => 'Section finances',
        'Základní dokumenty' => 'Core documents',
        'Formuláře' => 'Forms',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $index = 0;
        foreach (self::CATEGORIES as $name => $nameEn) {
            DocumentCategory::updateOrCreateByTranslation('name', $name, ['name' => ['cs' => $name, 'en' => $nameEn], 'sort_order' => $index]);
            $index++;
        }
    }
}
