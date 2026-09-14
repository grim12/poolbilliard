<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BannerSeeder extends Seeder
{
    /**
     * Mirrors ui/src/index.njk's eventBanner() call ("Federal Cup 2026") — the only real banner
     * example in ui/'s mock data. Homepage placement (which slot shows which banner, if any)
     * will be picked in HomepageSettings once that's built, not hardcoded here.
     */
    public function run(): void
    {
        $disk = Storage::disk('public');
        $imagePath = 'banners/federal.jpg';

        if (! $disk->exists($imagePath)) {
            $disk->put($imagePath, file_get_contents(database_path('seeders/assets/banners/federal.jpg')));
        }

        Banner::updateOrCreate(
            ['title' => 'Federal Cup 2026'],
            [
                'text' => 'Největší domácí akce sezóny. Tři dny zápasů, doprovodný program a přímé přenosy z hlavního stolu.',
                'image' => $imagePath,
                'tag_text' => 'ZA 2 MĚSÍCE',
                'meta_text' => '23. – 25. října 2026 · Bratislava',
                'button_text' => 'Detail akce',
                'button_url' => '#',
                'sort_order' => 0,
            ]
        );
    }
}
