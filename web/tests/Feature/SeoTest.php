<?php

namespace Tests\Feature;

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
}
