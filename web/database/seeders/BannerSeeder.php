<?php

namespace Database\Seeders;

use App\Enums\BannerColor;
use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BannerSeeder extends Seeder
{
    /**
     * Mirrors ui/src/index.njk's two homepage banner() calls — eventBanner() ("Federal Cup
     * 2026") and cta() ("Hraješ s kamarády..."). Both render through the same underlying
     * macros/banner.njk, just with different color/buttons, hence the shared Banner entity.
     * Homepage placement (which slot shows which banner, if any) is picked in HomepageSettings
     * once that's built, not hardcoded here.
     */
    private const BANNERS = [
        [
            'title' => 'Federal Cup 2026',
            'text' => '<p>Největší domácí akce sezóny. Tři dny zápasů, doprovodný program a přímé přenosy z hlavního stolu.</p>',
            'image' => 'federal.jpg',
            'tag_text' => 'ZA 2 MĚSÍCE',
            'meta_text' => '23. – 25. října 2026 · Bratislava',
            'color' => BannerColor::Accent,
            'buttons' => [
                ['text' => ['cs' => 'Detail akce'], 'url' => '#', 'variant' => 'solid'],
            ],
            'sort_order' => 0,
        ],
        [
            'title' => 'Hraješ s kamarády a chceš to zkusit závodně?',
            'text' => '<p>Přestaň jen koukat a začni hrát na turnajích. Najdi si klub, kde budeš moct trénovat, a staň se součástí komunity hráčů, kteří to myslí vážně.</p>',
            'image' => 'pool-group-blue.jpg',
            'tag_text' => 'Pro začátečníky',
            'meta_text' => null,
            'color' => BannerColor::Primary,
            'buttons' => [
                ['text' => ['cs' => 'Jak začít s poolem'], 'url' => '/jak-zacit', 'variant' => 'outline'],
                ['text' => ['cs' => 'Najdi si nejbližší klub'], 'url' => '/kluby', 'variant' => 'solid'],
            ],
            'sort_order' => 1,
        ],
    ];

    public function run(): void
    {
        $disk = Storage::disk('public');

        foreach (self::BANNERS as $data) {
            $imageFile = $data['image'];
            $imagePath = 'banners/'.$imageFile;

            if (! $disk->exists($imagePath)) {
                $disk->put($imagePath, file_get_contents(database_path('seeders/assets/banners/'.$imageFile)));
            }

            Banner::updateOrCreateByTranslation(
                'title',
                $data['title'],
                [...$data, 'image' => $imagePath]
            );
        }
    }
}
