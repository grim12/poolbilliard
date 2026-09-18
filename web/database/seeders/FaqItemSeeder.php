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
            'question_en' => 'What is Czech Pool?',
            'answer' => 'Český poolbilliard je sportovní sekce Českomoravského billiardového svazu (ČMBS), která zastřešuje soutěže, kluby a hráče poolbilliardu v České republice. Stará se o organizaci turnajů, rozvoj mládeže i reprezentaci na mezinárodní scéně.',
            'answer_en' => 'Czech Pool is the pool billiards section of the Czech-Moravian Billiards Federation (ČMBS), covering competitions, clubs, and players across the Czech Republic. It organizes tournaments, develops youth players, and runs the national team on the international stage.',
        ],
        [
            'question' => 'Jak mohu začít hrát poolbilliard?',
            'question_en' => 'How can I start playing pool billiards?',
            'answer' => 'Stačí najít nejbližší <a href="/herny/">kulečníkovou hernu</a> nebo <a href="/kluby/">klub</a> ve svém okolí. Podrobný návod pro začátečníky, rekreační hráče i rodiče najdete na stránce <a href="/jak-zacit/">Jak začít</a>.',
            'answer_en' => 'Just find the nearest <a href="/en/venues">billiards venue</a> or <a href="/en/clubs">club</a> near you. A detailed guide for beginners, recreational players, and parents is available on the <a href="/en/getting-started">Getting Started</a> page.',
        ],
        [
            'question' => 'Musím být členem klubu, abych si mohl zahrát?',
            'question_en' => 'Do I need to be a club member to play?',
            'answer' => 'Ne. Pro hru s kamarády v herně žádné členství nepotřebujete. Členství v klubu je potřeba až pro účast na soutěžích pořádaných ČMBS.',
            'answer_en' => "No. You don't need any membership to play with friends at a venue. Club membership is only required to take part in ČMBS-organized competitions.",
        ],
        [
            'question' => 'Kde najdu pravidla jednotlivých disciplín?',
            'question_en' => 'Where can I find the rules for each discipline?',
            'answer' => 'Kompletní pravidla pro 8-ball, 9-ball, 10-ball i 14.1 nekonečnou najdete na stránce <a href="/pravidla/">Pravidla poolbilliardu</a>, včetně vyvrácení nejčastějších mýtů mezi rekreačními hráči.',
            'answer_en' => 'The complete rules for 8-ball, 9-ball, 10-ball, and 14.1 straight pool are on the <a href="/en/rules">Pool Billiards Rules</a> page, along with the most common myths among recreational players debunked.',
        ],
        [
            'question' => 'Jak zaregistruji svou hernu do adresáře?',
            'question_en' => 'How do I register my venue in the directory?',
            'answer' => 'Provozovatelé heren mohou svou hernu zdarma přidat do katalogu přes formulář <a href="/registrace-herny/">Registrace herny</a>. Po schválení se herna objeví v seznamu i na interaktivní mapě.',
            'answer_en' => 'Venue operators can add their venue to the catalog for free via the <a href="/en/venue-registration">Venue Registration</a> form. Once approved, the venue appears in the list and on the interactive map.',
        ],
        [
            'question' => 'Jak se přihlásím na turnaj?',
            'question_en' => 'How do I enter a tournament?',
            'answer' => 'Aktuální kalendář turnajů a soutěží najdete na stránce <a href="/kalendar/">Kalendář</a>. Přihlášky na jednotlivé turnaje probíhají přes přihlašovací systém uvedený u konkrétní akce.',
            'answer_en' => 'The current tournament and competition calendar is on the <a href="/en/calendar">Calendar</a> page. Entries for individual tournaments go through the registration system listed for that specific event.',
        ],
        [
            'question' => 'Jaké soutěže ČMBS pořádá?',
            'question_en' => 'What competitions does ČMBS organize?',
            'answer' => 'Systém soutěží zahrnuje regionální ligy, Českou poolovou tour, Mistrovství České republiky, juniorské soutěže i týmové ligy. Přehled najdete na stránce <a href="/souteze/">Soutěže</a>.',
            'answer_en' => 'The competition system includes regional leagues, the Czech Pool Tour, the Czech Championship, junior competitions, and team leagues. An overview is on the <a href="/en/competitions">Competitions</a> page.',
        ],
        [
            'question' => 'Jak mohu kontaktovat výkonný výbor sekce?',
            'question_en' => 'How can I contact the executive committee?',
            'answer' => 'Napište na e-mail vvs.pool@cmbs.cz, nebo se podívejte na stránku <a href="/sportovni-svaz/">Sportovní svaz</a>, kde najdete kontakty na jednotlivé členy výkonného výboru.',
            'answer_en' => 'Email vvs.pool@cmbs.cz, or check the <a href="/en/association">Sports Federation</a> page for contact details of each executive committee member.',
        ],
        [
            'question' => 'Jak se dozvím o novinkách a výsledcích?',
            'question_en' => 'How do I find out about news and results?',
            'answer' => 'Sledujte <a href="/novinky/">novinky</a> na webu, nebo se přihlaste k odběru newsletteru níže — pošleme vám pravidelný přehled turnajů a výsledků.',
            'answer_en' => "Follow the <a href=\"/en/news\">news</a> section on the website, or sign up for the newsletter below — we'll send you a regular roundup of tournaments and results.",
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
                [
                    'question' => ['cs' => $item['question'], 'en' => $item['question_en']],
                    'answer' => ['cs' => $item['answer'], 'en' => $item['answer_en']],
                    'sort_order' => $index,
                ]
            );

            $faqItem->groups()->syncWithoutDetaching([$obecneGroupId]);
        }
    }
}
