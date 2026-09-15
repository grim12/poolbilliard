<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ArticleSeeder extends Seeder
{
    /**
     * Mirrors ui/src/novinky.njk's 10 inline article entries. `category` is a name lookup
     * against ArticleCategorySeeder's rows, resolved to article_category_id below. `image` is
     * the seeder-asset filename (see database/seeders/assets/articles/), copied onto the
     * "public" disk the same way as PartnerSeeder. "Český tým ovládl mistrovství Evropy v
     * Heyball!" additionally gets the full body + gallery from ui/'s one hardcoded
     * article-detail.njk example — see self::HEYBALL_EXTRA below.
     */
    private const ARTICLES = [
        ['title' => 'Český tým ovládl mistrovství Evropy v Heyball!', 'image' => 'heyball-me.jpg', 'category' => 'Reprezentace', 'published_at' => '2026-08-15', 'excerpt' => 'Reprezentační tým České republiky vybojoval na ME družstev v Heyball zlaté medaile a stal se evropským šampionem pro rok 2026.'],
        ['title' => 'Vítězem 1. kola Junior Open je Alexandr Hofmann', 'image' => 'junior-open-1-kolo.jpg', 'category' => 'Junior Open', 'published_at' => '2026-06-05', 'excerpt' => 'Mladý talent ovládl úvodní kolo seriálu Junior Open a potvrdil formu z minulé sezóny.'],
        ['title' => 'Matouš Vlk ovládl první kolo ČPTour 2026', 'image' => 'cptour-1-kolo.jpg', 'category' => 'ČPTour', 'published_at' => '2026-02-25', 'excerpt' => 'První turnaj sezóny přinesl vyrovnané souboje, ve finále ale nenašel přemožitele.'],
        ['title' => 'Petr Urban zvítězil na MR 10-ball v Brně', 'image' => 'mr-10-ball.jpg', 'category' => 'Mistrovství ČR', 'published_at' => '2026-04-12', 'excerpt' => 'Kvalifikace i mistrovství republiky se hrály ve stejném víkendu ve Slovanském domě.'],
        ['title' => 'MR Heyball vyhrála Veronika Hubrtová', 'image' => 'mr-heyball.jpg', 'category' => 'Mistrovství ČR', 'published_at' => '2026-08-05', 'excerpt' => 'Ve finále porazila obhájkyni titulu a získala první seniorský titul kariéry.'],
        ['title' => 'Junior Open 4. kolo přineslo nové tváře', 'image' => 'junior-open-4-kolo.jpg', 'category' => 'Junior Open', 'published_at' => '2026-10-20', 'excerpt' => 'Do bojů o body v žebříčku se poprvé zapojila i mladší kategorie do 14 let.'],
        ['title' => 'Federal Cup 2026 hostí Bratislava', 'image' => 'federal-cup.jpg', 'category' => 'Zpravodajství', 'published_at' => '2026-10-01', 'excerpt' => 'Největší domácí akce sezóny nabídne tři dny zápasů a doprovodný program.'],
        ['title' => 'Nové podmínky přestupů hráčů schváleny', 'image' => 'prestupy.jpg', 'category' => 'Zpravodajství', 'published_at' => '2026-07-15', 'excerpt' => 'Výkonný výbor schválil úpravu registračního řádu platnou od nové sezóny.'],
        ['title' => 'Aktualizovaná pravidla kulečníku ke stažení', 'image' => 'pravidla.jpg', 'category' => 'Zpravodajství', 'published_at' => '2026-03-03', 'excerpt' => 'Nové znění pravidel reflektuje poslední změny mezinárodní federace.'],
        ['title' => 'Jak začít hrát pool: kompletní návod pro začátečníky', 'image' => 'zacni-hrat.jpg', 'category' => 'Zpravodajství', 'published_at' => '2026-01-10', 'excerpt' => 'Od výběru klubu po první turnaj — připravili jsme přehledného průvodce.'],
    ];

    private const HEYBALL_BODY = <<<'HTML'
        <p>Český billiard slaví mimořádný mezinárodní úspěch. Reprezentační tým České republiky vybojoval na mistrovství Evropy družstev v Heyball zlaté medaile a stal se evropským šampionem pro rok 2026.</p>
        <p>O historický úspěch se zasloužil český reprezentační výběr ve složení:</p>
        <ul>
            <li><strong>Roman Hybler</strong></li>
            <li><strong>Petr Urban</strong></li>
            <li><strong>David „Gekon“ Žalman</strong></li>
            <li><strong>Martin Forman</strong></li>
        </ul>
        <p>Týmové mistrovství Evropy se uskutečnilo ve dnech 4. a 5. srpna 2026 a do soutěže mohlo nastoupit celkem deset reprezentačních týmů.</p>
        <h2>Jednoznačný vstup do turnaje</h2>
        <p>Čeští reprezentanti zahájili turnaj přesvědčivým vítězstvím <strong>3:0 nad Nizozemskem</strong>. David Žalman porazil Jona Kouse 3:0, Roman Hybler Quinna Pongerse rovněž 3:0 a zápas Petra Urbana s Kongem Oeiem byl za rozhodnutého stavu ukončen výsledkem 1:1.</p>
        <p>Také druhé utkání zvládlo české družstvo vítězně. Nad <strong>Rumunskem zvítězilo 3:1</strong>. Roman Hybler porazil Ioana Munteanua 3:1, Petr Urban Cristiana Verschatse 3:0 a rozhodující bod přidal David Žalman, který po předchozí porážce otočil svůj druhý zápas proti Claudiu Gîndacovi a zvítězil 3:0.</p>
        <p>Následovala první komplikace. V kvalifikačním utkání o přímý postup do závěrečné části podlehli Češi <strong>Chorvatsku 0:3</strong>. David Žalman prohrál s Robertem Bartolem 1:3, Roman Hybler s Andrejem Šolou 2:3 a Petr Urban s Filipem Bermancem 1:3.</p>
        <h3>Druhé vítězství nad Nizozemskem</h3>
        <p>Po porážce se český tým musel o postup do semifinále utkat znovu s Nizozemskem. Druhý vzájemný zápas byl oproti úvodnímu utkání mnohem vyrovnanější a nabídl řadu dramatických okamžiků.</p>
        <p>Česká republika nakonec zvítězila <strong>3:1</strong>. Roman Hybler porazil Konga Oeie 3:2, David Žalman Quinna Pongerse rovněž 3:2 a Petr Urban vedl nad Jonem Kousem 2:1 ve chvíli, kdy byl rozhodující třetí bod českého týmu potvrzen.</p>
        <p>David „Gekon“ Žalman dokázal svůj zápas otočit a zaujal především obtížným potopením černé koule pod úhlem do částečně zakryté kapsy.</p>
        <p>Velké drama nabídl také zápas Petra Urbana. Za stavu 1:1 musel při dohrávání posledních koulí několikrát řešit velmi složité pozice bílé koule. Přesto zachoval klid a postupně stůl dohrál. Zelená koule se po zásahu dlouho pohybovala v kapse, ale nakonec přece jen spadla. Následnou černou již český reprezentant bezpečně proměnil.</p>
        <p>Česká republika si vítězstvím zajistila postup mezi čtyři nejlepší týmy turnaje a zároveň jistotu medaile.</p>
        <h3>Semifinálový obrat proti Ukrajině</h3>
        <p>V semifinále čekala na český výběr silná Ukrajina. Ani nepříznivý začátek však české reprezentanty nezastavil.</p>
        <p>Roman Hybler nejprve podlehl Mykolovi Morozovi těsně 2:3. David Žalman ale následně porazil Ivana Rudenka 2:1 a Petr Urban přidal jednoznačné vítězství 3:0 nad Ivanem Lialinem.</p>
        <p>Definitivní postup do finále potvrdil Roman Hybler ve svém druhém zápase proti Morozovi. Tentokrát zvítězil 2:1 a český tým tak porazil <strong>Ukrajinu 3:1</strong>.</p>
        <h3>Ve finále výhra nad Gruzií</h3>
        <p>Soupeřem českého týmu ve finále byla Gruzie, která v semifinále vyřadila Chorvatsko výsledkem 3:2.</p>
        <p>Češi vstoupili do rozhodujícího utkání výborně. Roman Hybler porazil Davida Avakiana 3:0 a David Žalman přidal vítězství 3:2 nad Nikolozem Bakratzem.</p>
        <p>Gruzie poté snížila, když Erekle Topchiev porazil Petra Urbana 3:1. Rozhodující třetí bod však znovu získal Roman Hybler, který ve druhém střetnutí s Avakianem zvítězil 3:2.</p>
        <p>Česká republika tak ve finále porazila <strong>Gruzii 3:1</strong> a získala titul mistrů Evropy družstev v heyballu.</p>
        <h3>Cesta českého týmu za zlatem</h3>
        <p><strong>1. kolo:</strong> Česká republika – Nizozemsko <strong>3:0</strong></p>
        <p><strong>2. kolo:</strong> Česká republika – Rumunsko <strong>3:1</strong></p>
        <p><strong>Kvalifikace vítězů:</strong> Chorvatsko – Česká republika <strong>3:0</strong></p>
        <p><strong>Kvalifikace poražených:</strong> Nizozemsko – Česká republika <strong>1:3</strong></p>
        <p><strong>Semifinále:</strong> Ukrajina – Česká republika <strong>1:3</strong></p>
        <p><strong>Finále:</strong> Česká republika – Gruzie <strong>3:1</strong></p>
        <h3>Další evropské zlato pro český heyball</h3>
        <p>Týmový titul navazuje na individuální úspěch Romana Hyblera, který se v roce 2025 stal mistrem Evropy v heyball. Český heyball tak během krátké doby získal další mimořádně cenný výsledek a potvrdil, že čeští hráči patří v této rychle se rozvíjející disciplíně mezi evropskou špičku.</p>
        <p>Zlatá medaile z týmového mistrovství Evropy představuje velký úspěch nejen pro samotné reprezentanty, ale také pro celý český billiard.</p>
        HTML;

    private const HEYBALL_GALLERY = [
        'heyball-me.jpg',
        'junior-open-4-kolo.jpg',
        'cptour-1-kolo.jpg',
        'mr-10-ball.jpg',
        'mr-heyball.jpg',
        'junior-open-1-kolo.jpg',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disk = Storage::disk('public');

        foreach (self::ARTICLES as $data) {
            $categoryName = $data['category'];
            $imageFile = $data['image'];
            unset($data['category'], $data['image']);

            $imagePath = 'articles/'.$imageFile;

            if (! $disk->exists($imagePath)) {
                $disk->put($imagePath, file_get_contents(database_path('seeders/assets/articles/'.$imageFile)));
            }

            $extra = [];

            if ($data['title'] === 'Český tým ovládl mistrovství Evropy v Heyball!') {
                $extra['body'] = self::HEYBALL_BODY;
                $extra['gallery'] = collect(self::HEYBALL_GALLERY)->map(function (string $file) use ($disk) {
                    $path = 'articles/'.$file;

                    if (! $disk->exists($path)) {
                        $disk->put($path, file_get_contents(database_path('seeders/assets/articles/'.$file)));
                    }

                    return $path;
                })->all();
            }

            Article::updateOrCreateByTranslation(
                'title',
                $data['title'],
                [
                    ...$data,
                    ...$extra,
                    'image' => $imagePath,
                    'article_category_id' => ArticleCategory::whereJsonContainsLocale('name', 'cs', $categoryName)->value('id'),
                ]
            );
        }
    }
}
