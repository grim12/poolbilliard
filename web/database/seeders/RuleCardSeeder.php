<?php

namespace Database\Seeders;

use App\Models\RuleCard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RuleCardSeeder extends Seeder
{
    /**
     * Mirrors ui/src/pravidla.njk's 5 ruleCard() calls. Discipline images are committed under
     * database/seeders/assets/rule-cards/ (mirrored from ui/src/uploads/) and copied onto the
     * "public" storage disk here — same pattern as PartnerSeeder. "Obecná pravidla" has no
     * image (uses the `icon` heroicon fallback instead), matching ui/'s mock.
     */
    private const CARDS = [
        [
            'title' => 'Obecná pravidla',
            'subtitle' => 'Společná pro všechny disciplíny',
            'text' => 'Společný základ pro všechny disciplíny poolbilliardu. Pravidla rozehry, faulů, chování hráčů a další obecná ustanovení.',
            'icon' => 'book-open',
            'image' => null,
            'button_url' => '#',
        ],
        [
            'title' => '8-ball',
            'subtitle' => 'Osmička',
            'text' => 'Nejrozšířenější disciplína mezi amatérskými hráči. Hraje se s plnými a půlenými koulemi, vítězí ten, kdo legálně potopí osmičku.',
            'icon' => null,
            'image' => 'cesky-poolbilliard-8-ball.png',
            'image_alt' => '8-ball',
            'button_url' => '#',
        ],
        [
            'title' => '9-ball',
            'subtitle' => 'Devítka',
            'text' => 'Rychlá a dynamická hra s devíti koulemi. Vždy se hraje na nejnižší kouli na stole, vítězí ten, kdo potopí devítku.',
            'icon' => null,
            'image' => 'cesky-poolbilliard-9-ball.png',
            'image_alt' => '9-ball',
            'button_url' => '#',
        ],
        [
            'title' => '10-ball',
            'subtitle' => 'Desítka',
            'text' => 'Přísnější varianta devítky s deseti koulemi a povinným nahlášením koule i kapsy.',
            'icon' => null,
            'image' => 'cesky-poolbilliard-10-ball.png',
            'image_alt' => '10-ball',
            'button_url' => '#',
        ],
        [
            'title' => '14.1',
            'subtitle' => 'Nekonečná',
            'text' => 'Klasická hra na předem stanovený počet bodů. Strategická disciplína s důrazem na poziční hru.',
            'icon' => null,
            'image' => 'cesky-poolbilliard-14-1.png',
            'image_alt' => '14.1',
            'button_url' => '#',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assetsPath = database_path('seeders/assets/rule-cards');
        $disk = Storage::disk('public');

        foreach (self::CARDS as $index => $card) {
            $title = $card['title'];
            unset($card['title']);

            $image = $card['image'];

            if ($image) {
                $storagePath = "rule-cards/{$image}";

                if (! $disk->exists($storagePath)) {
                    $disk->put($storagePath, file_get_contents($assetsPath.'/'.$image));
                }

                $card['image'] = $storagePath;
            }

            RuleCard::updateOrCreateByTranslation(
                'title',
                $title,
                [...$card, 'sort_order' => $index]
            );
        }
    }
}
