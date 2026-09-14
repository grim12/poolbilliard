<?php

namespace Database\Seeders;

use App\Models\FaqGroup;
use Illuminate\Database\Seeder;

class FaqGroupSeeder extends Seeder
{
    /**
     * "obecne" is the global FAQ page (ui/src/faq.njk) — the only group that exists so far.
     * Page-specific groups get added here as pages that need their own FAQ block are built.
     */
    private const GROUPS = [
        ['name' => 'Obecné (stránka FAQ)', 'slug' => 'obecne'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::GROUPS as $index => $group) {
            FaqGroup::updateOrCreate(
                ['slug' => $group['slug']],
                ['name' => $group['name'], 'sort_order' => $index]
            );
        }
    }
}
