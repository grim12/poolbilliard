<?php

namespace Tests\Feature;

use App\Enums\HernaStatus;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Club;
use App\Models\Herna;
use App\Models\Tournament;
use App\Models\TournamentCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_robots_txt_disallows_everything_while_not_indexable(): void
    {
        config(['seo.indexable' => false]);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /', false);
    }

    public function test_robots_txt_only_disallows_admin_once_indexable(): void
    {
        config(['seo.indexable' => true]);

        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertSee('Disallow: /admin', false);
        $response->assertDontSee('Disallow: /'."\n", false);
        $response->assertSee('Sitemap: '.route('sitemap'), false);
    }

    /**
     * robots.txt must stay reachable even while SiteLock is locking every other page — a
     * redirect to the unlock screen here would break crawlers rather than just telling them
     * to stay away.
     */
    public function test_robots_txt_is_reachable_even_when_the_site_is_locked(): void
    {
        config(['sitelock.enabled' => true]);

        $this->get('/robots.txt')->assertOk();
    }

    public function test_meta_robots_tag_follows_indexable_state(): void
    {
        config(['seo.indexable' => false]);
        $this->get('/')->assertSee('noindex, nofollow, noarchive, nosnippet', false);

        config(['seo.indexable' => true]);
        $this->get('/')->assertSee('name="robots" content="index, follow"', false);
    }

    /**
     * The favicon/manifest links (and the files they point at) are shared sitewide markup, not
     * per-page content — the homepage stands in for every page here too.
     */
    public function test_homepage_links_the_favicon_set_and_manifest(): void
    {
        $response = $this->get('/');

        $response->assertSee('<link rel="icon" href="'.asset('favicon.ico').'"', false);
        $response->assertSee('<link rel="apple-touch-icon" sizes="180x180" href="'.asset('apple-touch-icon.png').'"', false);
        $response->assertSee('<link rel="manifest" href="'.asset('site.webmanifest').'"', false);

        foreach (['favicon.ico', 'favicon-16x16.png', 'favicon-32x32.png', 'apple-touch-icon.png', 'android-chrome-192x192.png', 'android-chrome-512x512.png', 'site.webmanifest'] as $file) {
            $this->assertFileExists(public_path($file));
        }
    }

    /**
     * Every page goes through <x-layouts.app>, so the homepage stands in for the shared
     * description/canonical/OG/Twitter markup every other page also gets.
     */
    public function test_homepage_includes_meta_description_and_og_tags(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<meta name="description" content="Oficiální web Českého svazu poolbilliardu', false);
        $response->assertSee('<link rel="canonical" href="'.url('/').'"', false);
        $response->assertSee('<meta property="og:title" content="Poolbilliard — Český svaz poolbilliardu"', false);
        $response->assertSee('<meta property="og:type" content="website"', false);
        $response->assertSee('<meta name="twitter:card" content="summary_large_image"', false);
    }

    /**
     * Article pages override og:type to "article" and derive the description from the
     * article's own excerpt (see clanek.blade.php) instead of a fixed sitewide sentence.
     */
    public function test_article_detail_page_has_article_og_type_and_excerpt_description(): void
    {
        $category = ArticleCategory::factory()->create();
        $article = Article::factory()->create([
            'article_category_id' => $category->id,
            'excerpt' => 'Testovací perex článku pro SEO popis.',
        ]);

        $response = $this->get(route('novinky.show', $article->slug_cs));

        $response->assertOk();
        $response->assertSee('<meta property="og:type" content="article"', false);
        $response->assertSee('<meta name="description" content="Testovací perex článku pro SEO popis."', false);
    }

    /**
     * Every page gets the sitewide SportsOrganization JSON-LD block (see <x-layouts.app>);
     * the homepage stands in for any page that doesn't add its own on top of it.
     */
    public function test_every_page_includes_the_sitewide_organization_structured_data(): void
    {
        $this->get('/')->assertSee('"@type":"SportsOrganization"', false);
    }

    /**
     * A News/Article-type page's JSON-LD headline must match the record it's actually for,
     * not just be present — otherwise a copy-paste mistake between pages would go unnoticed.
     */
    public function test_article_detail_page_includes_matching_news_article_structured_data(): void
    {
        $category = ArticleCategory::factory()->create();
        $article = Article::factory()->create([
            'article_category_id' => $category->id,
            'title' => 'Testovací titulek článku',
        ]);

        $response = $this->get(route('novinky.show', $article->slug_cs));

        $response->assertSee('"@type":"NewsArticle"', false);
        $response->assertSee('"headline":"Testovací titulek článku"', false);
    }

    /**
     * A tournament with a start_date gets a SportsEvent block; RecurringTournament has no single
     * date to put in one, so tournament pages are the only ones that need covering here.
     */
    public function test_tournament_detail_page_includes_sports_event_structured_data(): void
    {
        $category = TournamentCategory::factory()->create();
        $tournament = Tournament::factory()->create([
            'tournament_category_id' => $category->id,
            'title' => 'Testovací turnaj',
            'start_date' => '2026-11-01',
            'location_text' => 'Testovací herna, Praha',
        ]);

        $response = $this->get(route('turnaj.show', $tournament));

        $response->assertSee('"@type":"SportsEvent"', false);
        $response->assertSee('"name":"Testovací turnaj"', false);
        $response->assertSee('"startDate":"2026-11-01"', false);
    }

    public function test_sitemap_is_not_available_while_not_indexable(): void
    {
        config(['seo.indexable' => false]);

        $this->get('/sitemap.xml')->assertNotFound();
    }

    /**
     * Also covers that SiteLock lets /sitemap.xml through on its own merits (a plain redirect
     * to the unlock screen wouldn't be a 404) — see SiteLock's bypass list.
     */
    public function test_sitemap_is_not_available_while_the_site_is_locked(): void
    {
        config(['sitelock.enabled' => true]);

        $this->get('/sitemap.xml')->assertNotFound();
    }

    /**
     * Only what a visitor can actually reach: an approved Herna and a published Article are in,
     * a pending Herna (public submission not yet moderated) and an unpublished Article are out —
     * same visibility rules ArticleController/HernaController already enforce on the pages
     * themselves (see SeoController::sitemap()'s docblock).
     */
    public function test_sitemap_lists_public_urls_and_excludes_unpublished_ones(): void
    {
        config(['seo.indexable' => true]);

        $club = Club::factory()->create();
        $approvedHerna = Herna::factory()->create(['status' => HernaStatus::Approved]);
        $pendingHerna = Herna::factory()->create(['status' => HernaStatus::Pending]);
        $category = ArticleCategory::factory()->create();
        $publishedArticle = Article::factory()->create([
            'article_category_id' => $category->id,
            'published_at' => now(),
        ]);
        $unpublishedArticle = Article::factory()->create([
            'article_category_id' => $category->id,
            'published_at' => null,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('<loc>'.route('home').'</loc>', false);
        $response->assertSee('<loc>'.route('klub.show', $club).'</loc>', false);
        $response->assertSee('<loc>'.route('herna.show', $approvedHerna).'</loc>', false);
        $response->assertSee('<loc>'.route('novinky.show', $publishedArticle->slug_cs).'</loc>', false);
        $response->assertDontSee(route('herna.show', $pendingHerna), false);
        $response->assertDontSee(route('novinky.show', $unpublishedArticle->slug_cs), false);
    }
}
