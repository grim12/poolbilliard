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
        ['title' => 'Český tým ovládl mistrovství Evropy v Heyball!', 'title_en' => 'Czech team wins the Heyball European Championship!', 'image' => 'heyball-me.jpg', 'category' => 'Reprezentace', 'published_at' => '2026-08-15', 'excerpt' => 'Reprezentační tým České republiky vybojoval na ME družstev v Heyball zlaté medaile a stal se evropským šampionem pro rok 2026.', 'excerpt_en' => 'The Czech national team won gold at the Heyball Team European Championship, becoming European champion for 2026.'],
        ['title' => 'Vítězem 1. kola Junior Open je Alexandr Hofmann', 'title_en' => 'Alexandr Hofmann wins round 1 of Junior Open', 'image' => 'junior-open-1-kolo.jpg', 'category' => 'Junior Open', 'published_at' => '2026-06-05', 'excerpt' => 'Mladý talent ovládl úvodní kolo seriálu Junior Open a potvrdil formu z minulé sezóny.', 'excerpt_en' => 'The young talent dominated the opening round of the Junior Open series, confirming his form from last season.'],
        ['title' => 'Matouš Vlk ovládl první kolo ČPTour 2026', 'title_en' => 'Matouš Vlk wins round 1 of the 2026 Czech Pool Tour', 'image' => 'cptour-1-kolo.jpg', 'category' => 'ČPTour', 'published_at' => '2026-02-25', 'excerpt' => 'První turnaj sezóny přinesl vyrovnané souboje, ve finále ale nenašel přemožitele.', 'excerpt_en' => "The season's first tournament brought close matches, but he went unbeaten in the final."],
        ['title' => 'Petr Urban zvítězil na MR 10-ball v Brně', 'title_en' => 'Petr Urban wins the Czech 10-ball Championship in Brno', 'image' => 'mr-10-ball.jpg', 'category' => 'Mistrovství ČR', 'published_at' => '2026-04-12', 'excerpt' => 'Kvalifikace i mistrovství republiky se hrály ve stejném víkendu ve Slovanském domě.', 'excerpt_en' => 'Qualification and the national championship were held on the same weekend at Slovanský dům.'],
        ['title' => 'MR Heyball vyhrála Veronika Hubrtová', 'title_en' => 'Veronika Hubrtová wins the Czech Heyball Championship', 'image' => 'mr-heyball.jpg', 'category' => 'Mistrovství ČR', 'published_at' => '2026-08-05', 'excerpt' => 'Ve finále porazila obhájkyni titulu a získala první seniorský titul kariéry.', 'excerpt_en' => 'She beat the defending champion in the final to claim her first senior title.'],
        ['title' => 'Junior Open 4. kolo přineslo nové tváře', 'title_en' => 'Round 4 of Junior Open brings new faces', 'image' => 'junior-open-4-kolo.jpg', 'category' => 'Junior Open', 'published_at' => '2026-10-20', 'excerpt' => 'Do bojů o body v žebříčku se poprvé zapojila i mladší kategorie do 14 let.', 'excerpt_en' => 'The younger under-14 category joined the fight for ranking points for the first time.'],
        ['title' => 'Federal Cup 2026 hostí Bratislava', 'title_en' => 'Bratislava hosts Federal Cup 2026', 'image' => 'federal-cup.jpg', 'category' => 'Zpravodajství', 'published_at' => '2026-10-01', 'excerpt' => 'Největší domácí akce sezóny nabídne tři dny zápasů a doprovodný program.', 'excerpt_en' => "The season's biggest domestic event offers three days of matches and a side program."],
        ['title' => 'Nové podmínky přestupů hráčů schváleny', 'title_en' => 'New player transfer rules approved', 'image' => 'prestupy.jpg', 'category' => 'Zpravodajství', 'published_at' => '2026-07-15', 'excerpt' => 'Výkonný výbor schválil úpravu registračního řádu platnou od nové sezóny.', 'excerpt_en' => 'The executive committee approved an update to the registration rules, effective from the new season.'],
        ['title' => 'Aktualizovaná pravidla kulečníku ke stažení', 'title_en' => 'Updated billiards rules available for download', 'image' => 'pravidla.jpg', 'category' => 'Zpravodajství', 'published_at' => '2026-03-03', 'excerpt' => 'Nové znění pravidel reflektuje poslední změny mezinárodní federace.', 'excerpt_en' => 'The new wording reflects the latest changes from the international federation.'],
        ['title' => 'Jak začít hrát pool: kompletní návod pro začátečníky', 'title_en' => "How to start playing pool: a complete beginner's guide", 'image' => 'zacni-hrat.jpg', 'category' => 'Zpravodajství', 'published_at' => '2026-01-10', 'excerpt' => 'Od výběru klubu po první turnaj — připravili jsme přehledného průvodce.', 'excerpt_en' => "From picking a club to your first tournament — we've put together a clear guide."],
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

    private const HEYBALL_BODY_EN = <<<'HTML'
        <p>Czech billiards is celebrating an extraordinary international success. The Czech national team won gold at the Heyball Team European Championship, becoming European champion for 2026.</p>
        <p>The historic achievement was earned by the Czech national squad, made up of:</p>
        <ul>
            <li><strong>Roman Hybler</strong></li>
            <li><strong>Petr Urban</strong></li>
            <li><strong>David "Gekon" Žalman</strong></li>
            <li><strong>Martin Forman</strong></li>
        </ul>
        <p>The team European championship took place on August 4 and 5, 2026, with a total of ten national teams competing.</p>
        <h2>A commanding start to the tournament</h2>
        <p>The Czech team opened the tournament with a convincing <strong>3:0 win over the Netherlands</strong>. David Žalman beat Jon Kous 3:0, Roman Hybler beat Quinn Pongers also 3:0, and Petr Urban's match against Kong Oei was stopped at 1:1 once the tie was already decided.</p>
        <p>The Czech team also won its second match. It beat <strong>Romania 3:1</strong>. Roman Hybler beat Ioan Munteanu 3:1, Petr Urban beat Cristian Verschats 3:0, and the decisive point was added by David Žalman, who turned around his second match against Claudiu Gîndac after an earlier loss, winning 3:0.</p>
        <p>The first complication followed. In the qualifying match for direct progression to the final stage, the Czechs lost <strong>0:3 to Croatia</strong>. David Žalman lost to Robert Bartol 1:3, Roman Hybler lost to Andrej Šola 2:3, and Petr Urban lost to Filip Bermanec 1:3.</p>
        <h3>A second win over the Netherlands</h3>
        <p>After the loss, the Czech team had to face the Netherlands again for a place in the semifinal. The rematch was much closer than the first meeting and offered a number of dramatic moments.</p>
        <p>The Czech Republic ultimately won <strong>3:1</strong>. Roman Hybler beat Kong Oei 3:2, David Žalman also beat Quinn Pongers 3:2, and Petr Urban led Jon Kous 2:1 at the moment the Czech team's decisive third point was confirmed.</p>
        <p>David "Gekon" Žalman managed to turn his match around, most notably potting a difficult angled black into a partially covered pocket.</p>
        <p>Petr Urban's match also offered plenty of drama. At 1:1, he had to work through several very difficult cue-ball positions while clearing the last balls. Even so, he stayed calm and worked through the table. The green ball hovered in the jaws of the pocket for a long time before finally dropping. The Czech player then safely potted the black that followed.</p>
        <p>With the win, the Czech Republic secured a place among the tournament's top four teams and, with it, a guaranteed medal.</p>
        <h3>A semifinal comeback against Ukraine</h3>
        <p>A strong Ukrainian side awaited the Czech squad in the semifinal. Even an unfavorable start didn't stop the Czech players.</p>
        <p>Roman Hybler first lost narrowly to Mykola Moroz 2:3. But David Žalman then beat Ivan Rudenko 2:1, and Petr Urban added a clear 3:0 win over Ivan Lialin.</p>
        <p>Roman Hybler confirmed the decisive progression to the final in his second match against Moroz. This time he won 2:1, and the Czech team beat <strong>Ukraine 3:1</strong>.</p>
        <h3>A final win over Georgia</h3>
        <p>The Czech team's opponent in the final was Georgia, who had eliminated Croatia 3:2 in the semifinal.</p>
        <p>The Czechs made an excellent start to the deciding match. Roman Hybler beat David Avakian 3:0, and David Žalman added a 3:2 win over Nikoloz Bakradze.</p>
        <p>Georgia then pulled one back, as Erekle Topchiev beat Petr Urban 3:1. But the decisive third point again went to Roman Hybler, who won his second meeting with Avakian 3:2.</p>
        <p>The Czech Republic thus beat <strong>Georgia 3:1</strong> in the final and won the Heyball Team European Championship title.</p>
        <h3>The Czech team's road to gold</h3>
        <p><strong>Round 1:</strong> Czech Republic – Netherlands <strong>3:0</strong></p>
        <p><strong>Round 2:</strong> Czech Republic – Romania <strong>3:1</strong></p>
        <p><strong>Winners' qualifier:</strong> Croatia – Czech Republic <strong>3:0</strong></p>
        <p><strong>Losers' qualifier:</strong> Netherlands – Czech Republic <strong>1:3</strong></p>
        <p><strong>Semifinal:</strong> Ukraine – Czech Republic <strong>1:3</strong></p>
        <p><strong>Final:</strong> Czech Republic – Georgia <strong>3:1</strong></p>
        <h3>Another European gold for Czech heyball</h3>
        <p>The team title follows Roman Hybler's individual success, who became European heyball champion in 2025. Czech heyball has thus earned another exceptionally valuable result in a short span of time, confirming that Czech players rank among Europe's best in this fast-growing discipline.</p>
        <p>The gold medal from the team European championship is a huge success not only for the players themselves, but for Czech billiards as a whole.</p>
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
            $title = $data['title'];
            $categoryName = $data['category'];
            $imageFile = $data['image'];
            unset($data['category'], $data['image']);

            $data['title'] = ['cs' => $data['title'], 'en' => $data['title_en']];
            $data['excerpt'] = ['cs' => $data['excerpt'], 'en' => $data['excerpt_en']];
            unset($data['title_en'], $data['excerpt_en']);

            $imagePath = 'articles/'.$imageFile;

            if (! $disk->exists($imagePath)) {
                $disk->put($imagePath, file_get_contents(database_path('seeders/assets/articles/'.$imageFile)));
            }

            $extra = [];

            if ($title === 'Český tým ovládl mistrovství Evropy v Heyball!') {
                $extra['body'] = ['cs' => self::HEYBALL_BODY, 'en' => self::HEYBALL_BODY_EN];
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
                $title,
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
