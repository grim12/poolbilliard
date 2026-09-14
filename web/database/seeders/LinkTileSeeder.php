<?php

namespace Database\Seeders;

use App\Models\LinkTile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class LinkTileSeeder extends Seeder
{
    /**
     * Mirrors ui/src/index.njk and faq.njk's identical linkTiles() call — same 4 tiles
     * duplicated on both pages there, unified here into one reusable pool. Which page shows
     * which tiles (and in what order) is decided per-page, not by this seeder — see
     * skills/web-component-guide.md.
     */
    private const TILES = [
        ['title' => 'Začni hrát', 'url' => '/jak-zacit', 'image' => 'zacni-hrat.jpg'],
        ['title' => 'Pravidla', 'url' => '/pravidla', 'image' => 'pravidla.jpg'],
        ['title' => 'Systémy soutěží', 'url' => '/souteze', 'image' => 'systemy-soutezi.jpg'],
        ['title' => 'O svazu', 'url' => '/sportovni-svaz', 'image' => 'o-svazu.jpg'],
    ];

    public function run(): void
    {
        $disk = Storage::disk('public');

        foreach (self::TILES as $index => $data) {
            $imageFile = $data['image'];
            $imagePath = 'link-tiles/'.$imageFile;

            if (! $disk->exists($imagePath)) {
                $disk->put($imagePath, file_get_contents(database_path('seeders/assets/link-tiles/'.$imageFile)));
            }

            LinkTile::updateOrCreate(
                ['title' => $data['title']],
                [
                    'url' => $data['url'],
                    'image' => $imagePath,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
