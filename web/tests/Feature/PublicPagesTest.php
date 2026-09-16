<?php

namespace Tests\Feature;

use App\Enums\HernaStatus;
use App\Enums\Region;
use App\Enums\Sport;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Banner;
use App\Models\Club;
use App\Models\ClubMember;
use App\Models\CommitteeMember;
use App\Models\CompetitionSection;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\FaqGroup;
use App\Models\FaqItem;
use App\Models\Herna;
use App\Models\JakZacitSection;
use App\Models\Leaderboard;
use App\Models\LinkTile;
use App\Models\Myth;
use App\Models\Notice;
use App\Models\Partner;
use App\Models\RecurringTournament;
use App\Models\RuleCard;
use App\Models\Tournament;
use App\Models\TournamentCategory;
use App\Settings\GeneralSettings;
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
        JakZacitSection::factory()->create();
        CommitteeMember::factory()->create();

        foreach (['/', '/partneri', '/faq', '/pravidla', '/kalendar', '/souteze', '/jak-zacit', '/sportovni-svaz', '/novinky', '/zpravodajstvi/vykonny-vybor', '/kluby', '/herny', '/registrace-herny'] as $url) {
            $response = $this->get($url);

            $response->assertOk();
            $response->assertSee('c-header__nav', false);
            $response->assertSee('c-footer__nav', false);
        }
    }

    /**
     * The header's light/dark variant (.c-header vs. .c-header.t-dark) is driven sitewide by
     * GeneralSettings::$header_dark (see <x-layouts.app>'s docblock), not a per-page choice —
     * light is the default/primary look.
     */
    public function test_header_dark_variant_follows_general_settings_toggle(): void
    {
        $this->get('/')->assertSee('class="c-header"', false);

        app(GeneralSettings::class)->header_dark = true;
        app(GeneralSettings::class)->save();

        $this->get('/')->assertSee('class="c-header t-dark"', false);
    }

    /**
     * The public "Registrace herny" form creates the Herna directly with HernaStatus::Pending
     * (same table real approved herny use — see RegistraceHernyController's docblock), so it
     * must never appear on the public /herny listing until an admin approves it.
     */
    public function test_registrace_herny_form_creates_pending_herna_and_shows_thank_you(): void
    {
        $response = $this->post(route('registrace-herny.store'), [
            'name' => 'Testovací herna',
            'description' => 'Popis herny',
            'address' => 'Ulice 1',
            'city' => 'Testov',
            'region' => Region::Praha->value,
            'sports' => [Sport::Poolbilliard->value],
        ]);

        $response->assertRedirect(route('registrace-herny'));
        $response->assertSessionHas('submitted', true);

        $herna = Herna::where('name', 'Testovací herna')->firstOrFail();
        $this->assertSame(HernaStatus::Pending, $herna->status);

        // Must come first — the 'submitted' flash only survives one more request.
        $this->get(route('registrace-herny'))->assertSee('Děkujeme za registraci');
        $this->get('/herny')->assertDontSee('Testovací herna');
    }

    public function test_registrace_herny_form_requires_name_and_at_least_one_sport(): void
    {
        $response = $this->post(route('registrace-herny.store'), [
            'description' => 'Popis herny',
            'address' => 'Ulice 1',
            'city' => 'Testov',
            'region' => Region::Praha->value,
        ]);

        $response->assertSessionHasErrors(['name', 'sports']);
        $this->assertSame(0, Herna::count());
    }

    /**
     * The hidden "company" honeypot field must stay empty — a bot that fills every input trips
     * it (App\Http\Requests\StoreHernaRegistrationRequest's `prohibited` rule).
     */
    public function test_registrace_herny_form_rejects_honeypot_submissions(): void
    {
        $response = $this->post(route('registrace-herny.store'), [
            'company' => 'Not empty',
            'name' => 'Testovací herna',
            'description' => 'Popis herny',
            'address' => 'Ulice 1',
            'city' => 'Testov',
            'region' => Region::Praha->value,
            'sports' => [Sport::Poolbilliard->value],
        ]);

        $response->assertSessionHasErrors('company');
        $this->assertSame(0, Herna::count());
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
     * /pravidla: Myth records render via <x-myth-faq> (first item pre-opened,
     * aria-expanded="true") and RuleCard records via <x-rule-card>. Both are separate entities
     * from PravidlaSettings (just the page's 3 section headers) — Myth in particular started
     * as a PravidlaSettings repeater, then got promoted to its own model/resource once it
     * became clear myths might appear elsewhere later (see MythResource).
     */
    public function test_pravidla_page_renders_myths_and_rule_cards(): void
    {
        Myth::factory()->create([
            'myth_text' => 'Testovací mýtus',
            'correct_text' => '<p>Testovací správné pravidlo</p>',
        ]);
        RuleCard::factory()->create([
            'title' => 'Testovací disciplína',
            'text' => '<p>Testovací pravidlo</p>',
        ]);

        $response = $this->get('/pravidla');

        $response->assertOk();
        $response->assertSee('Testovací disciplína');
        $response->assertSee('Testovací pravidlo', false);
        $response->assertSee('Testovací mýtus');
        $response->assertSee('Testovací správné pravidlo', false);
        // First item must be pre-opened, not just present.
        $response->assertSee('aria-expanded="true" aria-controls="mytus-panel-1"', false);
    }

    /**
     * /jak-zacit: JakZacitSection records render their steps (<x-steps>), structured aside
     * (info panel + link card, chosen over a freeform RichEditor to keep 1:1 visual fidelity —
     * see JakZacitSection's docblock), and a per-section FAQ block sourced from a FaqGroup whose
     * slug matches the section's anchor (JakZacitSection::faqItems()).
     */
    public function test_jak_zacit_page_renders_section_steps_aside_and_faq(): void
    {
        JakZacitSection::factory()->create([
            'anchor' => 'test-cesta',
            'nav_label' => 'Test cesta',
            'title' => 'Testovací cesta',
            'intro' => '<p>Testovací úvodní text</p>',
            'steps' => [
                ['icon' => 'map-pin', 'title' => '1. Testovací krok', 'text' => '<p>Text testovacího kroku</p>'],
            ],
            'aside_panel_title' => 'Testovací panel',
            'aside_card_title' => 'Testovací karta',
            'faq_title' => 'Testovací FAQ titulek',
        ]);
        $group = FaqGroup::factory()->create(['slug' => 'test-cesta']);
        $faqItem = FaqItem::factory()->create([
            'question' => 'Testovací otázka',
            'answer' => 'Testovací odpověď',
        ]);
        $faqItem->groups()->attach($group);

        $response = $this->get('/jak-zacit');

        $response->assertOk();
        $response->assertSee('Testovací cesta');
        $response->assertSee('id="test-cesta"', false);
        $response->assertSee('Testovací úvodní text', false);
        $response->assertSee('1. Testovací krok');
        $response->assertSee('Text testovacího kroku', false);
        $response->assertSee('Testovací panel');
        $response->assertSee('Testovací karta');
        $response->assertSee('Testovací FAQ titulek');
        $response->assertSee('Testovací otázka');
        $response->assertSee('Testovací odpověď');
    }

    /**
     * /sportovni-svaz: CommitteeMember records render via <x-committee-list>, and the document
     * archive (<x-documents>) groups real Document records by year — a document with a year
     * gets its own year tab, one without (year: null) falls into the trailing "Obecné" tab
     * (SvazController::buildDocumentYears()).
     */
    public function test_svaz_page_renders_committee_members_and_document_archive(): void
    {
        CommitteeMember::factory()->create(['name' => 'Testovací člen', 'role' => 'Testovací role']);
        $category = DocumentCategory::factory()->create(['name' => 'Testovací kategorie']);
        Document::factory()->create([
            'document_category_id' => $category->id,
            'year' => 2026,
            'name' => 'Testovací dokument 2026',
        ]);
        Document::factory()->create([
            'document_category_id' => $category->id,
            'year' => null,
            'name' => 'Testovací evergreen dokument',
        ]);

        $response = $this->get('/sportovni-svaz');

        $response->assertOk();
        $response->assertSee('Testovací člen');
        $response->assertSee('Testovací role');
        $response->assertSee('2026');
        $response->assertSee('Obecné');
        $response->assertSee('Testovací kategorie');
        $response->assertSee('Testovací dokument 2026');
        $response->assertSee('Testovací evergreen dokument');
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
