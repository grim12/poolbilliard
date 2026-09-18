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
            'title_en' => 'General rules',
            'subtitle' => 'Společná pro všechny disciplíny',
            'subtitle_en' => 'Common to every discipline',
            'text' => 'Společný základ pro všechny disciplíny poolbilliardu. Pravidla rozehry, faulů, chování hráčů a další obecná ustanovení.',
            'text_en' => 'The shared foundation for every pool billiards discipline. Rules for breaking, fouls, player conduct, and other general provisions.',
            'icon' => 'book-open',
            'image' => null,
            'button_url' => '#',
        ],
        [
            'title' => '8-ball',
            'title_en' => '8-ball',
            'subtitle' => 'Osmička',
            'subtitle_en' => 'Eight-ball',
            'text' => 'Nejrozšířenější disciplína mezi amatérskými hráči. Hraje se s plnými a půlenými koulemi, vítězí ten, kdo legálně potopí osmičku.',
            'text_en' => 'The most widely played discipline among amateur players. Played with solids and stripes — whoever legally pots the black wins.',
            'icon' => null,
            'image' => 'cesky-poolbilliard-8-ball.png',
            'image_alt' => '8-ball',
            'button_url' => '#',
        ],
        [
            'title' => '9-ball',
            'title_en' => '9-ball',
            'subtitle' => 'Devítka',
            'subtitle_en' => 'Nine-ball',
            'text' => 'Rychlá a dynamická hra s devíti koulemi. Vždy se hraje na nejnižší kouli na stole, vítězí ten, kdo potopí devítku.',
            'text_en' => 'A fast, dynamic game played with nine balls. You always play the lowest-numbered ball on the table — whoever pots the nine wins.',
            'icon' => null,
            'image' => 'cesky-poolbilliard-9-ball.png',
            'image_alt' => '9-ball',
            'button_url' => '#',
        ],
        [
            'title' => '10-ball',
            'title_en' => '10-ball',
            'subtitle' => 'Desítka',
            'subtitle_en' => 'Ten-ball',
            'text' => 'Přísnější varianta devítky s deseti koulemi a povinným nahlášením koule i kapsy.',
            'text_en' => 'A stricter variant of nine-ball played with ten balls, requiring both the ball and pocket to be called.',
            'icon' => null,
            'image' => 'cesky-poolbilliard-10-ball.png',
            'image_alt' => '10-ball',
            'button_url' => '#',
        ],
        [
            'title' => '14.1',
            'title_en' => '14.1',
            'subtitle' => 'Nekonečná',
            'subtitle_en' => 'Straight pool',
            'text' => 'Klasická hra na předem stanovený počet bodů. Strategická disciplína s důrazem na poziční hru.',
            'text_en' => 'A classic game played to a set target score. A strategic discipline that puts a premium on position play.',
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
            $card['title'] = ['cs' => $card['title'], 'en' => $card['title_en']];
            $card['subtitle'] = ['cs' => $card['subtitle'], 'en' => $card['subtitle_en']];
            $card['text'] = ['cs' => $card['text'], 'en' => $card['text_en']];
            unset($card['title_en'], $card['subtitle_en'], $card['text_en']);

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
