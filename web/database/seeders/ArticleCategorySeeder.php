<?php

namespace Database\Seeders;

use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Mirrors the tagText/tagColor pairs used across ui/src/novinky.njk + article-detail.njk.
     * Every occurrence of a given tagText there uses the same tagColor (color is really a
     * property of the category, not freely chosen per article) — one inconsistency was found
     * (article-detail.njk's "Reprezentace" used tagColor="primary" once, vs. "accent"
     * everywhere else for that same category) and resolved in favor of the majority usage.
     */
    private const CATEGORIES = [
        ['name' => 'Reprezentace', 'color' => 'accent'],
        ['name' => 'Junior Open', 'color' => 'gold'],
        ['name' => 'ČPTour', 'color' => 'primary'],
        ['name' => 'Mistrovství ČR', 'color' => 'primary'],
        ['name' => 'Zpravodajství', 'color' => 'gray'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::CATEGORIES as $index => $category) {
            ArticleCategory::updateOrCreateByTranslation(
                'name',
                $category['name'],
                ['color' => $category['color'], 'sort_order' => $index]
            );
        }
    }
}
