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
                'answer' => 'Ne, herny i kluby obvykle zapůjčí tága i ostatní vybavení zdarma nebo za symbolický poplatek. Vlastní tágo se hodí, až budete hrát pravidelně.',
            ],
            [
                'question' => 'Kolik stojí hodina poolu?',
                'answer' => 'Cena se liší podle herny a lokality, obvykle se pohybuje v řádu stovek korun za hodinu na jeden stůl.',
            ],
            [
                'question' => 'Musím něco umět, abych mohl hrát?',
                'answer' => 'Ne, stačí základní představa o pravidlech. Personál herny vám rád poradí a hraní s kamarády je hlavně o zábavě.',
            ],
            [
                'question' => 'Kolik lidí potřebuji?',
                'answer' => 'Ideálně 2–4 hráče na jeden stůl, ale zahrát si můžete i sami s trenérem nebo ve dvojici.',
            ],
            [
                'question' => 'Je nutná rezervace?',
                'answer' => 'Ve větších městech a o víkendech doporučujeme stůl zamluvit předem, mimo špičku obvykle stačí přijít.',
            ],
            [
                'question' => 'Kolik let musím mít?',
                'answer' => 'Poolbilliard mohou hrát děti i dospělí bez omezení věku, herny obvykle mají večerní pravidla pro mladistvé.',
            ],
        ],
        'rekreacni-hrac' => [
            [
                'question' => 'Kolik stojí členství v klubu?',
                'answer' => 'Členské příspěvky se liší klub od klubu, obvykle v řádu stokorun až tisícikorun ročně.',
            ],
            [
                'question' => 'Jak často se trénuje?',
                'answer' => 'Většina klubů nabízí tréninky jednou až dvakrát týdně, záleží na kapacitě herny a trenérech.',
            ],
            [
                'question' => 'Musím být registrovaný ve svazu?',
                'answer' => 'Pro účast na turnajích ČMBS ano, registrace je jednoduchá a klub vám s ní pomůže.',
            ],
            [
                'question' => 'Jak probíhá registrace?',
                'answer' => 'Klub vás zaregistruje přes systém svazu, stačí vyplnit základní údaje a uhradit registrační poplatek.',
            ],
            [
                'question' => 'Mohu hrát za víc klubů?',
                'answer' => 'Ne, hráč může být v jednom okamžiku registrován vždy jen za jeden klub.',
            ],
        ],
        'rodic' => [
            [
                'question' => 'Je poolbilliard vhodný pro děti?',
                'answer' => 'Ano, rozvíjí soustředění, strategické myšlení a jemnou motoriku, hraje se bez fyzického kontaktu.',
            ],
            [
                'question' => 'Od kolika let mohou děti začít?',
                'answer' => 'Většina klubů přijímá děti od cca 8 let, záleží na konkrétním klubu a jeho podmínkách.',
            ],
            [
                'question' => 'Jak často se trénuje?',
                'answer' => 'Juniorské tréninky obvykle probíhají jednou až dvakrát týdně po škole nebo o víkendech.',
            ],
            [
                'question' => 'Kolik stojí členství v klubu?',
                'answer' => 'Členské příspěvky se liší, mnoho klubů nabízí zvýhodněné podmínky pro mládež.',
            ],
            [
                'question' => 'Jak probíhá registrace?',
                'answer' => 'Klub dítě zaregistruje do systému svazu, rodič obvykle jen podepíše souhlas a uhradí poplatek.',
            ],
            [
                'question' => 'Musí být dítě registrováno ve svazu?',
                'answer' => 'Pro účast na Junior Open a Mistrovství ČR juniorů ano, bez soutěží stačí členství v klubu.',
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

                if ($faqItem) {
                    $faqItem->update(['answer' => $item['answer'], 'sort_order' => $index]);
                } else {
                    $faqItem = FaqItem::create([
                        'question' => $item['question'],
                        'answer' => $item['answer'],
                        'sort_order' => $index,
                    ]);
                    $faqItem->groups()->attach($group->id);
                }
            }
        }
    }
}
