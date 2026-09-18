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
            'title_en' => 'Federal Cup 2026',
            'text' => '<p>Největší domácí akce sezóny. Tři dny zápasů, doprovodný program a přímé přenosy z hlavního stolu.</p>',
            'text_en' => '<p>The biggest domestic event of the season. Three days of matches, a side program, and live streams from the main table.</p>',
            'image' => 'federal.jpg',
            'tag_text' => 'ZA 2 MĚSÍCE',
            'tag_text_en' => 'IN 2 MONTHS',
            'meta_text' => '23. – 25. října 2026 · Bratislava',
            'meta_text_en' => 'October 23–25, 2026 · Bratislava',
            'color' => BannerColor::Accent,
            'buttons' => [
                ['text' => ['cs' => 'Detail akce', 'en' => 'Event details'], 'url' => '#', 'variant' => 'solid'],
            ],
            'sort_order' => 0,
        ],
        [
            'title' => 'Hraješ s kamarády a chceš to zkusit závodně?',
            'title_en' => 'Playing with friends and want to try competing?',
            'text' => '<p>Přestaň jen koukat a začni hrát na turnajích. Najdi si klub, kde budeš moct trénovat, a staň se součástí komunity hráčů, kteří to myslí vážně.</p>',
            'text_en' => '<p>Stop just watching and start playing tournaments. Find a club where you can train and become part of a community of players who take it seriously.</p>',
            'image' => 'pool-group-blue.jpg',
            'tag_text' => 'Pro začátečníky',
            'tag_text_en' => 'For beginners',
            'meta_text' => null,
            'meta_text_en' => null,
            'color' => BannerColor::Primary,
            'buttons' => [
                ['text' => ['cs' => 'Jak začít s poolem', 'en' => 'How to get started'], 'url' => '/jak-zacit', 'variant' => 'outline'],
                ['text' => ['cs' => 'Najdi si nejbližší klub', 'en' => 'Find your nearest club'], 'url' => '/kluby', 'variant' => 'solid'],
            ],
            'sort_order' => 1,
        ],
    ];

    public function run(): void
    {
        $disk = Storage::disk('public');

        foreach (self::BANNERS as $data) {
            $title = $data['title'];
            $imageFile = $data['image'];
            $imagePath = 'banners/'.$imageFile;

            if (! $disk->exists($imagePath)) {
                $disk->put($imagePath, file_get_contents(database_path('seeders/assets/banners/'.$imageFile)));
            }

            $data['title'] = ['cs' => $data['title'], 'en' => $data['title_en']];
            $data['text'] = ['cs' => $data['text'], 'en' => $data['text_en']];
            $data['tag_text'] = ['cs' => $data['tag_text'], 'en' => $data['tag_text_en']];
            $data['meta_text'] = ['cs' => $data['meta_text'], 'en' => $data['meta_text_en']];
            unset($data['title_en'], $data['text_en'], $data['tag_text_en'], $data['meta_text_en']);

            Banner::updateOrCreateByTranslation(
                'title',
                $title,
                [...$data, 'image' => $imagePath]
            );
        }
    }
}
