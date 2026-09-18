<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PartnerSeeder extends Seeder
{
    /**
     * Partners shown on the homepage teaser and the full "Partneři" directory —
     * mirrors ui/src/_data/partneri.json. Logo files are committed under
     * database/seeders/assets/partners/ (source assets, mirrored from
     * ui/src/uploads/) and copied onto the "public" storage disk here, since
     * storage/app/public/ itself is gitignored (regenerable, not source).
     */
    private const PARTNERS = [
        ['name' => 'WPA – World Pool Association', 'logo' => '1-WPA-logo.webp', 'url' => '#'],
        ['name' => 'EPBF – European Pocket Billiard Federation', 'logo' => '2-EPBF-logo.png', 'url' => '#'],
        ['name' => 'WCBS – World Confederation of Billiards Sports', 'logo' => '3-WCBS-logo.png', 'url' => '#'],
        ['name' => 'ČUS – Česká unie sportu', 'logo' => '4-CUS-logo.png', 'url' => '#'],
        ['name' => 'NSA – Národní sportovní agentura', 'logo' => '5-NSA-logo.svg', 'url' => '#'],
        ['name' => 'ČUKIS', 'logo' => '6-CUKIS-logo.png', 'url' => '#'],
        ['name' => 'VIS', 'logo' => '7-VIS-logo.jpg', 'url' => '#'],
        ['name' => 'Billiard Teska', 'logo' => '8-TESKA-logo.png', 'url' => '#'],
        ['name' => 'NITTIN', 'logo' => 'nittin-logo.png', 'url' => '#'],
        ['name' => 'bCreative', 'logo' => 'bcreative-logo.png', 'url' => '#'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assetsPath = database_path('seeders/assets/partners');
        $disk = Storage::disk('public');

        foreach (self::PARTNERS as $index => $partner) {
            $storagePath = "partners/{$partner['logo']}";

            if (! $disk->exists($storagePath)) {
                $disk->put($storagePath, file_get_contents($assetsPath.'/'.$partner['logo']));
            }

            Partner::updateOrCreate(
                ['name' => $partner['name']],
                [
                    'logo' => $storagePath,
                    'url' => $partner['url'],
                    'sort_order' => $index,
                ]
            );
        }
    }
}
