<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\TranslationLoader\LanguageLine;

/**
 * Seeds every sitewide UI string translated via a plain `__('Nějaký český text')` call in Blade
 * (nav/footer chrome, aria-labels, button labels...) — replaces lang/en.json, which used to be
 * the only place these lived (see App\Filament\Resources\Translations\TranslationResource's
 * docblock for how the DB-backed loader takes over from here). Add a new pair here whenever a
 * new hardcoded string gets wrapped in __() — the admin can then edit its English translation
 * (and, optionally, override the Czech) without another deploy.
 */
class LanguageLineSeeder extends Seeder
{
    private const LINES = [
        'Novinky' => 'News',
        'Články' => 'Articles',
        'Zprávy výkonného výboru' => 'Executive Committee News',
        'Kalendář' => 'Calendar',
        'Kde hrát' => 'Where to Play',
        'Kluby' => 'Clubs',
        'Herny' => 'Venues',
        'Soutěže' => 'Competitions',
        'Jak začít' => 'Getting Started',
        'Jsem začátečník' => "I'm a Beginner",
        'Jsem rekreační hráč' => "I'm a Recreational Player",
        'Jsem rodič' => "I'm a Parent",
        'Pravidla kulečníku' => 'Billiards Rules',
        'Svaz' => 'Federation',
        'Český pool — domů' => 'Czech Pool — Home',
        'Hlavní navigace' => 'Main navigation',
        'Registrace na turnaje' => 'Tournament Registration',
        'Hledat' => 'Search',
        'Otevřít menu' => 'Open menu',
        'Zavřít vyhledávání' => 'Close search',
        'Hledat kluby, hráče, novinky…' => 'Search clubs, players, news…',
        'Přepnout jazyk' => 'Switch language',
        'Centrální platforma Českého poolbilliardu, sportovní sekce, která je součástí Českomoravského billiardového svazu.' => 'The central platform of Czech Pool, the billiards section of the Czech-Moravian Billiards Federation.',
        'Odkazy v patičce' => 'Footer links',
        'Hraj' => 'Play',
        'Začni' => 'Get Started',
        'Najdi si klub' => 'Find a Club',
        'Najdi si hernu' => 'Find a Venue',
        'Časté dotazy' => 'FAQ',
        'Sportovní svaz' => 'Sports Federation',
        'Výkonný výbor' => 'Executive Committee',
        'Partneři' => 'Partners',
        'Český poolbilliard' => 'Czech Pool Billiards',
        'Vytvořeno s ❤ pro českou poolovou komunitu' => 'Made with ❤ for the Czech pool community',
    ];

    public function run(): void
    {
        foreach (self::LINES as $cs => $en) {
            LanguageLine::updateOrCreate(
                ['group' => '*', 'key' => $cs],
                ['text' => ['en' => $en]]
            );
        }
    }
}
