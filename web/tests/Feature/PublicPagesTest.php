<?php

namespace Tests\Feature;

use App\Models\FaqItem;
use App\Models\Partner;
use App\Models\Tournament;
use App\Models\TournamentCategory;
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
        $category = TournamentCategory::factory()->create();
        Tournament::factory()->create(['tournament_category_id' => $category->id]);

        foreach (['/partneri', '/faq', '/turnaje'] as $url) {
            $response = $this->get($url);

            $response->assertOk();
            $response->assertSee('c-header__nav', false);
            $response->assertSee('c-footer__nav', false);
        }
    }
}
