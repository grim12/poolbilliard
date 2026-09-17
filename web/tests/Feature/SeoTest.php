<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
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
}
