<?php

namespace Database\Seeders;

use App\Models\FaqGroup;
use App\Models\FaqItem;
use Illuminate\Database\Seeder;

class JakZacitFaqItemSeeder extends Seeder
{
    /**
     * Mirrors the 3 inline faq() calls in ui/src/jak-zacit.njk — one group of questions per
     * audience path (see FaqGroupSeeder's "zacatecnik"/"rekreacni-hrac"/"rodic" groups, matched
     * to JakZacitSection::$anchor).
     */
    private const GROUPS = [
        'zacatecnik' => [
            [
                'question' => 'Potřebuji vlastní vybavení?',
                'question_en' => 'Do I need my own equipment?',
                'answer' => 'Ne, herny i kluby obvykle zapůjčí tága i ostatní vybavení zdarma nebo za symbolický poplatek. Vlastní tágo se hodí, až budete hrát pravidelně.',
                'answer_en' => 'No, venues and clubs usually lend cues and other equipment for free or for a token fee. A cue of your own comes in handy once you play regularly.',
            ],
            [
                'question' => 'Kolik stojí hodina poolu?',
                'question_en' => 'How much does an hour of pool cost?',
                'answer' => 'Cena se liší podle herny a lokality, obvykle se pohybuje v řádu stovek korun za hodinu na jeden stůl.',
                'answer_en' => 'Prices vary by venue and location, but typically run a few hundred crowns per hour per table.',
            ],
            [
                'question' => 'Musím něco umět, abych mohl hrát?',
                'question_en' => 'Do I need to know anything to play?',
                'answer' => 'Ne, stačí základní představa o pravidlech. Personál herny vám rád poradí a hraní s kamarády je hlavně o zábavě.',
                'answer_en' => 'No, a basic idea of the rules is enough. Venue staff are happy to help, and playing with friends is mostly about having fun.',
            ],
            [
                'question' => 'Kolik lidí potřebuji?',
                'question_en' => 'How many people do I need?',
                'answer' => 'Ideálně 2–4 hráče na jeden stůl, ale zahrát si můžete i sami s trenérem nebo ve dvojici.',
                'answer_en' => 'Ideally 2–4 players per table, but you can also play alone with a coach or as a pair.',
            ],
            [
                'question' => 'Je nutná rezervace?',
                'question_en' => 'Is a reservation required?',
                'answer' => 'Ve větších městech a o víkendech doporučujeme stůl zamluvit předem, mimo špičku obvykle stačí přijít.',
                'answer_en' => 'In larger cities and on weekends, we recommend booking a table in advance; outside peak times, walking in is usually fine.',
            ],
            [
                'question' => 'Kolik let musím mít?',
                'question_en' => 'Is there a minimum age?',
                'answer' => 'Poolbilliard mohou hrát děti i dospělí bez omezení věku, herny obvykle mají večerní pravidla pro mladistvé.',
                'answer_en' => 'Pool billiards can be played by children and adults alike, with no age limit — venues typically have their own evening rules for minors.',
            ],
        ],
        'rekreacni-hrac' => [
            [
                'question' => 'Kolik stojí členství v klubu?',
                'question_en' => 'How much does club membership cost?',
                'answer' => 'Členské příspěvky se liší klub od klubu, obvykle v řádu stokorun až tisícikorun ročně.',
                'answer_en' => 'Membership fees vary from club to club, typically a few hundred to a few thousand crowns a year.',
            ],
            [
                'question' => 'Jak často se trénuje?',
                'question_en' => 'How often are training sessions?',
                'answer' => 'Většina klubů nabízí tréninky jednou až dvakrát týdně, záleží na kapacitě herny a trenérech.',
                'answer_en' => 'Most clubs offer training once or twice a week, depending on venue capacity and coaches.',
            ],
            [
                'question' => 'Musím být registrovaný ve svazu?',
                'question_en' => 'Do I need to register with the federation?',
                'answer' => 'Pro účast na turnajích ČMBS ano, registrace je jednoduchá a klub vám s ní pomůže.',
                'answer_en' => 'Yes, to take part in ČMBS tournaments — registration is simple and your club will help you with it.',
            ],
            [
                'question' => 'Jak probíhá registrace?',
                'question_en' => 'How does registration work?',
                'answer' => 'Klub vás zaregistruje přes systém svazu, stačí vyplnit základní údaje a uhradit registrační poplatek.',
                'answer_en' => "Your club registers you through the federation's system — you just fill in basic details and pay the registration fee.",
            ],
            [
                'question' => 'Mohu hrát za víc klubů?',
                'question_en' => 'Can I play for more than one club?',
                'answer' => 'Ne, hráč může být v jednom okamžiku registrován vždy jen za jeden klub.',
                'answer_en' => 'No, a player can only be registered with one club at a time.',
            ],
        ],
        'rodic' => [
            [
                'question' => 'Je poolbilliard vhodný pro děti?',
                'question_en' => 'Is pool billiards suitable for children?',
                'answer' => 'Ano, rozvíjí soustředění, strategické myšlení a jemnou motoriku, hraje se bez fyzického kontaktu.',
                'answer_en' => 'Yes — it builds concentration, strategic thinking, and fine motor skills, and involves no physical contact.',
            ],
            [
                'question' => 'Od kolika let mohou děti začít?',
                'question_en' => 'At what age can children start?',
                'answer' => 'Většina klubů přijímá děti od cca 8 let, záleží na konkrétním klubu a jeho podmínkách.',
                'answer_en' => 'Most clubs accept children from around age 8, depending on the specific club and its conditions.',
            ],
            [
                'question' => 'Jak často se trénuje?',
                'question_en' => 'How often is training?',
                'answer' => 'Juniorské tréninky obvykle probíhají jednou až dvakrát týdně po škole nebo o víkendech.',
                'answer_en' => 'Junior training usually takes place once or twice a week after school or on weekends.',
            ],
            [
                'question' => 'Kolik stojí členství v klubu?',
                'question_en' => 'How much does club membership cost?',
                'answer' => 'Členské příspěvky se liší, mnoho klubů nabízí zvýhodněné podmínky pro mládež.',
                'answer_en' => 'Membership fees vary, and many clubs offer discounted rates for young players.',
            ],
            [
                'question' => 'Jak probíhá registrace?',
                'question_en' => 'How does registration work?',
                'answer' => 'Klub dítě zaregistruje do systému svazu, rodič obvykle jen podepíše souhlas a uhradí poplatek.',
                'answer_en' => "The club registers the child in the federation's system — a parent usually just signs consent and pays the fee.",
            ],
            [
                'question' => 'Musí být dítě registrováno ve svazu?',
                'question_en' => 'Does my child need to be registered with the federation?',
                'answer' => 'Pro účast na Junior Open a Mistrovství ČR juniorů ano, bez soutěží stačí členství v klubu.',
                'answer_en' => 'Yes, to take part in Junior Open and the Czech Junior Championship — without competitions, club membership is enough.',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * Unlike FaqItemSeeder's single "obecne" group, a few questions here share the exact same
     * wording across groups (e.g. "Jak probíhá registrace?") but with a different, audience-
     * specific answer — matching by question text alone (updateOrCreateByTranslation) would
     * collapse them into one shared row and overwrite one group's answer with the other's. So
     * matching here is scoped to "already in this group" first, falling back to a fresh record.
     */
    public function run(): void
    {
        foreach (self::GROUPS as $slug => $items) {
            $group = FaqGroup::where('slug', $slug)->first();

            foreach ($items as $index => $item) {
                $faqItem = $group->items
                    ->first(fn (FaqItem $candidate) => $candidate->getTranslation('question', 'cs') === $item['question']);

                $answer = ['cs' => $item['answer'], 'en' => $item['answer_en']];

                if ($faqItem) {
                    $faqItem->update(['answer' => $answer, 'sort_order' => $index]);
                } else {
                    $faqItem = FaqItem::create([
                        'question' => ['cs' => $item['question'], 'en' => $item['question_en']],
                        'answer' => $answer,
                        'sort_order' => $index,
                    ]);
                    $faqItem->groups()->attach($group->id);
                }
            }
        }
    }
}
