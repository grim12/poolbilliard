<?php

namespace Tests\Feature;

use App\Enums\HernaStatus;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Banner;
use App\Models\Club;
use App\Models\ClubMember;
use App\Models\CompetitionSection;
use App\Models\FaqItem;
use App\Models\Herna;
use App\Models\Leaderboard;
use App\Models\LinkTile;
use App\Models\Notice;
use App\Models\Partner;
use App\Models\RecurringTournament;
use App\Models\RuleCard;
use App\Models\Tournament;
use App\Models\TournamentCategory;
use App\Settings\HomepageSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Smoke test: every public page renders (200) and includes the shared site chrome
     * (<x-layouts.app> — header nav + footer), not just its own content. A page that forgets
     * to wrap in the layout would still return 200 with plain content, so check for chrome
     * markers explicitly.
     */
    public function test_public_pages_render_with_site_chrome(): void
    {
        Partner::factory()->create();
        FaqItem::factory()->create();
        $tournamentCategory = TournamentCategory::factory()->create();
        Tournament::factory()->create(['tournament_category_id' => $tournamentCategory->id]);
        $articleCategory = ArticleCategory::factory()->create();
        Article::factory()->create(['article_category_id' => $articleCategory->id]);
        Notice::factory()->create();
        Club::factory()->create();
        Herna::factory()->create(['status' => HernaStatus::Approved]);

        foreach (['/', '/partneri', '/faq', '/pravidla', '/kalendar', '/souteze', '/novinky', '/zpravodajstvi/vykonny-vybor', '/kluby', '/herny'] as $url) {
            $response = $this->get($url);

            $response->assertOk();
            $response->assertSee('c-header__nav', false);
            $response->assertSee('c-footer__nav', false);
        }
    }

    public function test_article_detail_page_renders(): void
    {
        $category = ArticleCategory::factory()->create();
        $article = Article::factory()->create([
            'article_category_id' => $category->id,
            'body' => '<p>Test body</p>',
        ]);

        $response = $this->get(route('novinky.show', $article->slug_cs));

        $response->assertOk();
        $response->assertSee($article->title);
        $response->assertSee('Test body', false);
    }

    /**
     * Exercises the parts test_public_pages_render_with_site_chrome's bare factory create()
     * doesn't reach: members, the recruitment alert, and the aside collapsing to a single
     * column when address/ambassador are both empty (no map card, no ambassador card).
     */
    public function test_club_detail_page_renders(): void
    {
        $club = Club::factory()->create([
            'address' => '',
            'ambassador_name' => null,
            'ambassador_website' => null,
            'about_text' => '<p>O klubu text</p>',
            'recruitment_open' => false,
        ]);
        ClubMember::factory()->for($club)->create(['name' => 'Testovací člen']);

        $response = $this->get(route('klub.show', $club));

        $response->assertOk();
        $response->assertSee($club->name);
        $response->assertSee('Testovací člen');
        $response->assertSee('Nábor uzavřen');
        // about_text is RichEditor-authored HTML — must render raw, not escaped (regression
        // check for the {{ }} vs {!! !!} mixup this originally shipped with).
        $response->assertSee('<p>O klubu text</p>', false);
    }

    /**
     * Same "everything optional collapses cleanly" idea as the club test above, but for the
     * herna detail page's own optional blocks: sports tags, opening hours, contact card.
     */
    public function test_herna_detail_page_renders(): void
    {
        $herna = Herna::factory()->create([
            'status' => HernaStatus::Approved,
            'about_text' => '<p>O herně text</p>',
            'sports' => ['Poolbilliard', 'Snooker'],
            'hours' => [['day' => 'Pondělí', 'text' => '14:00–24:00']],
            'phone' => '+420123456789',
        ]);

        $response = $this->get(route('herna.show', $herna));

        $response->assertOk();
        $response->assertSee($herna->name);
        $response->assertSee('Poolbilliard');
        $response->assertSee('14:00–24:00');
        $response->assertSee('+420123456789');
        // about_text is RichEditor-authored HTML — must render raw, not escaped (regression
        // check for the {{ }} vs {!! !!} mixup this originally shipped with).
        $response->assertSee('<p>O herně text</p>', false);
    }

    public function test_notice_detail_page_renders(): void
    {
        $notice = Notice::factory()->create([
            'is_important' => true,
            'body' => '<p>Test notice body</p>',
        ]);

        $response = $this->get(route('zpravodajstvi.vykonny-vybor.show', $notice->slug_cs));

        $response->assertOk();
        $response->assertSee($notice->title);
        $response->assertSee('Test notice body', false);
        $response->assertSee('Důležité');
    }

    public function test_tournament_detail_page_renders(): void
    {
        $category = TournamentCategory::factory()->create(['name' => 'ČMBS']);
        $tournament = Tournament::factory()->create([
            'tournament_category_id' => $category->id,
            'url' => 'https://vysledky.cmbs.cz/turnaje/test',
            'description' => '<p>Testovací popis turnaje</p>',
        ]);

        $response = $this->get(route('turnaj.show', $tournament));

        $response->assertOk();
        $response->assertSee($tournament->title);
        $response->assertSee('ČMBS');
        $response->assertSee('Testovací popis turnaje', false);
        $response->assertSee('https://vysledky.cmbs.cz/turnaje/test', false);
    }

    /**
     * The month mini-calendar's prev/next navigation is real (unlike the Svaz/Klub/Zahraniční
     * checkbox filter next to it) — a tournament dated in a different month must only get an
     * event pill on that month's grid, not the default (current month) one. The tournament
     * itself still appears in the card list either way (that list isn't month-filtered) — the
     * calendar-grid event pill (`.c-calendar__event-label`) is checked specifically, since it's
     * the one piece of the page that's actually month-scoped.
     */
    public function test_calendar_month_navigation_shows_real_data_for_the_requested_month(): void
    {
        $category = TournamentCategory::factory()->create();
        $tournament = Tournament::factory()->create([
            'title' => 'Říjnový turnaj',
            'tournament_category_id' => $category->id,
            'start_date' => '2026-10-15',
            'end_date' => null,
        ]);
        $eventPill = 'c-calendar__event-label">'.$tournament->title;

        $this->travelTo(now()->setDate(2026, 9, 1));

        $septemberResponse = $this->get('/kalendar');
        $septemberResponse->assertOk();
        $septemberResponse->assertSee('Září 2026');
        $septemberResponse->assertDontSee($eventPill, false);

        $octoberResponse = $this->get('/kalendar?month=2026-10');
        $octoberResponse->assertOk();
        $octoberResponse->assertSee('Říjen 2026');
        $octoberResponse->assertSee($eventPill, false);
    }

    /**
     * /souteze is the "hub" page: numbered CompetitionSection records rendered via
     * <x-content-section> (with a real named-slot `aside`), a jump-nav derived from them, and
     * the full Leaderboard listing (unlike homepage's top-5-per-board teaser).
     */
    public function test_souteze_page_renders_sections_and_full_leaderboards(): void
    {
        CompetitionSection::factory()->create([
            'anchor' => 'test-sekce',
            'nav_label' => 'Test sekce',
            'title' => 'Testovací sekce',
            'body' => '<p>Testovací obsah sekce</p>',
            'aside' => '<p>Testovací postranní karta</p>',
        ]);
        Leaderboard::factory()->create([
            'title' => 'Testovací žebříček',
            'entries' => collect(range(1, 10))->map(fn (int $i) => ['name' => "Hráč {$i}", 'club' => null])->all(),
        ]);

        $response = $this->get('/souteze');

        $response->assertOk();
        $response->assertSee('Test sekce');
        $response->assertSee('id="test-sekce"', false);
        $response->assertSee('Testovací obsah sekce', false);
        $response->assertSee('Testovací postranní karta', false);
        $response->assertSee('Hráč 10');
    }

    /**
     * /pravidla: PravidlaSettings' myths repeater renders via <x-myth-faq> (first item
     * pre-opened, aria-expanded="true") and RuleCard records via <x-rule-card>.
     */
    public function test_pravidla_page_renders_myths_and_rule_cards(): void
    {
        RuleCard::factory()->create([
            'title' => 'Testovací disciplína',
            'text' => '<p>Testovací pravidlo</p>',
        ]);

        $response = $this->get('/pravidla');

        $response->assertOk();
        $response->assertSee('Testovací disciplína');
        $response->assertSee('Testovací pravidlo', false);
        // Seeded myth data (see RuleCardSeeder/PravidlaSettings defaults) — first item must be
        // pre-opened, not just present.
        $response->assertSee('aria-expanded="true" aria-controls="mytus-panel-1"', false);
        $response->assertSee('protější kapsy');
    }

    public function test_recurring_tournament_detail_page_renders_and_optionally_links_to_herna(): void
    {
        $herna = Herna::factory()->create(['name' => 'Testovací herna', 'status' => HernaStatus::Approved]);
        $withHerna = RecurringTournament::factory()->create([
            'title' => 'Turnaje v Testovně',
            'frequency' => 'Každé pondělí',
            'herna_id' => $herna->id,
            'description' => '<p>Testovací popis pravidelného turnaje</p>',
        ]);
        $withoutHerna = RecurringTournament::factory()->create([
            'title' => 'Turnaje bez herny',
            'herna_id' => null,
        ]);

        $responseWithHerna = $this->get(route('pravidelny-turnaj.show', $withHerna));
        $responseWithHerna->assertOk();
        $responseWithHerna->assertSee($withHerna->title);
        $responseWithHerna->assertSee('Testovací popis pravidelného turnaje', false);
        $responseWithHerna->assertSee(route('herna.show', $herna), false);

        $responseWithoutHerna = $this->get(route('pravidelny-turnaj.show', $withoutHerna));
        $responseWithoutHerna->assertOk();
        $responseWithoutHerna->assertSee($withoutHerna->title);
        $responseWithoutHerna->assertDontSee('c-tournament-detail__card-title">Odkazy', false);
    }

    /**
     * Homepage-specific behavior beyond the plain chrome smoke test above: a HomepageSettings
     * banner slot with no selection must skip that section entirely (not render an empty
     * <x-banner>), and Leaderboard only shows its top 5 of 10 stored entries here (the full 10
     * is reserved for a future /souteze page).
     */
    public function test_homepage_renders_selected_content_and_hides_empty_banner_slot(): void
    {
        $articleCategory = ArticleCategory::factory()->create();
        Article::factory()->create(['article_category_id' => $articleCategory->id]);
        $banner = Banner::factory()->create(['title' => 'Testovací banner']);
        $linkTile = LinkTile::factory()->create(['title' => 'Testovací dlaždice']);
        Leaderboard::factory()->create([
            'entries' => collect(range(1, 10))->map(fn (int $i) => ['name' => "Hráč {$i}", 'club' => null])->all(),
        ]);

        app(HomepageSettings::class)->fill([
            'banner_1_id' => $banner->id,
            'banner_2_id' => null,
            'link_tile_ids' => [$linkTile->id],
        ])->save();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Testovací banner');
        $response->assertSee('Testovací dlaždice');
        $response->assertSee('Hráč 5');
        $response->assertDontSee('Hráč 6');
        $response->assertSee('c-section--event-banner', false);
        $response->assertDontSee('c-section--cta', false);
    }

    /**
     * When Banner 2 is empty, Tournaments and Leaderboards (both bg-gray-100) become directly
     * adjacent with no white section between them — per skills/ui-component-guide.md's
     * documented pattern, the shared inner seam must drop its border on both sides (Tournaments
     * keeps border-top, drops border-bottom; Leaderboards keeps border-bottom, drops border-top)
     * and the following section (Leaderboards) drops its top padding to avoid a doubled gap.
     */
    public function test_homepage_drops_inner_border_and_padding_when_banner_2_is_empty(): void
    {
        app(HomepageSettings::class)->fill(['banner_2_id' => null])->save();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('c-section--cta', false);
        $response->assertSee('c-section--tournaments bg-gray-100 border-top"', false);
        $response->assertSee('c-section--leaderboards bg-gray-100 border-bottom pt-none"', false);
    }
}
