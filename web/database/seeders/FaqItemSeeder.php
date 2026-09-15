<?php

namespace Database\Seeders;

use App\Models\FaqGroup;
use App\Models\FaqItem;
use Illuminate\Database\Seeder;

class FaqItemSeeder extends Seeder
{
    /**
     * Mirrors the inline items= array in ui/src/faq.njk. Answers keep their embedded <a>
     * links as raw HTML (rendered via {!! !!} in resources/views/components/faq.blade.php,
     * same as ui/'s `| safe` filter — trusted, admin-authored content).
     */
    private const ITEMS = [
        [
            'question' => 'Co je Český poolbilliard?',
            'answer' => 'Český poolbilliard je sportovní sekce Českomoravského billiardového svazu (ČMBS), která zastřešuje soutěže, kluby a hráče poolbilliardu v České republice. Stará se o organizaci turnajů, rozvoj mládeže i reprezentaci na mezinárodní scéně.',
        ],
        [
            'question' => 'Jak mohu začít hrát poolbilliard?',
            'answer' => 'Stačí najít nejbližší <a href="/herny/">kulečníkovou hernu</a> nebo <a href="/kluby/">klub</a> ve svém okolí. Podrobný návod pro začátečníky, rekreační hráče i rodiče najdete na stránce <a href="/jak-zacit/">Jak začít</a>.',
        ],
        [
            'question' => 'Musím být členem klubu, abych si mohl zahrát?',
            'answer' => 'Ne. Pro hru s kamarády v herně žádné členství nepotřebujete. Členství v klubu je potřeba až pro účast na soutěžích pořádaných ČMBS.',
        ],
        [
            'question' => 'Kde najdu pravidla jednotlivých disciplín?',
            'answer' => 'Kompletní pravidla pro 8-ball, 9-ball, 10-ball i 14.1 nekonečnou najdete na stránce <a href="/pravidla/">Pravidla poolbilliardu</a>, včetně vyvrácení nejčastějších mýtů mezi rekreačními hráči.',
        ],
        [
            'question' => 'Jak zaregistruji svou hernu do adresáře?',
            'answer' => 'Provozovatelé heren mohou svou hernu zdarma přidat do katalogu přes formulář <a href="/registrace-herny/">Registrace herny</a>. Po schválení se herna objeví v seznamu i na interaktivní mapě.',
        ],
        [
            'question' => 'Jak se přihlásím na turnaj?',
            'answer' => 'Aktuální kalendář turnajů a soutěží najdete na stránce <a href="/kalendar/">Kalendář</a>. Přihlášky na jednotlivé turnaje probíhají přes přihlašovací systém uvedený u konkrétní akce.',
        ],
        [
            'question' => 'Jaké soutěže ČMBS pořádá?',
            'answer' => 'Systém soutěží zahrnuje regionální ligy, Českou poolovou tour, Mistrovství České republiky, juniorské soutěže i týmové ligy. Přehled najdete na stránce <a href="/souteze/">Soutěže</a>.',
        ],
        [
            'question' => 'Jak mohu kontaktovat výkonný výbor sekce?',
            'answer' => 'Napište na e-mail vvs.pool@cmbs.cz, nebo se podívejte na stránku <a href="/sportovni-svaz/">Sportovní svaz</a>, kde najdete kontakty na jednotlivé členy výkonného výboru.',
        ],
        [
            'question' => 'Jak se dozvím o novinkách a výsledcích?',
            'answer' => 'Sledujte <a href="/novinky/">novinky</a> na webu, nebo se přihlaste k odběru newsletteru níže — pošleme vám pravidelný přehled turnajů a výsledků.',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obecneGroupId = FaqGroup::where('slug', 'obecne')->value('id');

        foreach (self::ITEMS as $index => $item) {
            $faqItem = FaqItem::updateOrCreateByTranslation(
                'question',
                $item['question'],
                ['answer' => $item['answer'], 'sort_order' => $index]
            );

            $faqItem->groups()->syncWithoutDetaching([$obecneGroupId]);
        }
    }
}
