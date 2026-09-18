<?php

namespace Database\Seeders;

use App\Models\FaqGroup;
use Illuminate\Database\Seeder;

class FaqGroupSeeder extends Seeder
{
    /**
     * "obecne" is the global FAQ page (ui/src/faq.njk). The 3 "jak-zacit-*" groups back the
     * per-audience FAQ blocks on /jak-zacit (JakZacitSection::$anchor must match each slug —
     * see JakZacitSection::faqItems()). Page-specific groups get added here as pages that need
     * their own FAQ block are built.
     */
    private const GROUPS = [
        ['name' => 'Obecné (stránka FAQ)', 'slug' => 'obecne'],
        ['name' => 'Jak začít — Úplný začátečník', 'slug' => 'zacatecnik'],
        ['name' => 'Jak začít — Rekreační hráč', 'slug' => 'rekreacni-hrac'],
        ['name' => 'Jak začít — Rodič', 'slug' => 'rodic'],
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
