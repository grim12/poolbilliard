<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
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
        $tournamentCategory = TournamentCategory::factory()->create();
        Tournament::factory()->create(['tournament_category_id' => $tournamentCategory->id]);
        $articleCategory = ArticleCategory::factory()->create();
        Article::factory()->create(['article_category_id' => $articleCategory->id]);

        foreach (['/partneri', '/faq', '/turnaje', '/novinky'] as $url) {
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

        $response = $this->get(route('novinky.show', $article->slug));

        $response->assertOk();
        $response->assertSee($article->title);
        $response->assertSee('Test body', false);
    }
}
