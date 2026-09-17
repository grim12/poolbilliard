<?php

namespace Tests\Feature;

use App\Enums\HernaStatus;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Club;
use App\Models\Herna;
use App\Models\Notice;
use App\Models\RecurringTournament;
use App\Models\Tournament;
use App\Models\TournamentCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnLocaleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Every static /en/* route (translated path segments — see routes/web.php's docblock)
     * renders and sets app()->getLocale() to "en" for the whole request — that's what actually
     * switches translatable model attributes and __() calls, not the URL by itself.
     */
    public function test_static_en_routes_render_and_switch_the_app_locale(): void
    {
        foreach (['/en', '/en/partners', '/en/faq', '/en/rules', '/en/calendar', '/en/competitions', '/en/getting-started', '/en/association', '/en/news', '/en/committee-news', '/en/clubs', '/en/venues'] as $url) {
            $this->get($url)->assertOk();
            $this->assertSame('en', app()->getLocale(), "app locale wasn't 'en' after GET {$url}");
        }
    }

    /**
     * The Czech routes must keep working unaffected (app locale back to "cs") — the SetLocale
     * middleware is only attached to the /en group, so it must not leak across requests either.
     */
    public function test_cs_routes_keep_the_default_locale(): void
    {
        $this->get('/')->assertOk();

        $this->assertSame('cs', app()->getLocale());
    }

    /**
     * {model:slug_en} binding actually resolves via the model's own slug_en column, not slug_cs
     * — regression coverage for a copy-paste mistake in the /en route definitions.
     */
    public function test_en_detail_pages_bind_via_slug_en(): void
    {
        $club = Club::factory()->create(['slug_cs' => 'test-klub-cs', 'slug_en' => 'test-club-en']);
        $this->get('/en/club/test-club-en')->assertOk();
        $this->get('/en/club/test-klub-cs')->assertNotFound();

        $approvedHerna = Herna::factory()->create(['status' => HernaStatus::Approved, 'slug_cs' => 'test-herna-cs', 'slug_en' => 'test-venue-en']);
        $this->get('/en/venue/test-venue-en')->assertOk();

        $category = TournamentCategory::factory()->create();
        $tournament = Tournament::factory()->create(['tournament_category_id' => $category->id, 'slug_cs' => 'turnaj-cs', 'slug_en' => 'tournament-en']);
        $this->get('/en/tournament/tournament-en')->assertOk();

        $recurring = RecurringTournament::factory()->create(['slug_cs' => 'pravidelny-cs', 'slug_en' => 'recurring-en']);
        $this->get('/en/recurring-tournament/recurring-en')->assertOk();

        $articleCategory = ArticleCategory::factory()->create();
        $article = Article::factory()->create(['article_category_id' => $articleCategory->id, 'slug_cs' => 'clanek-cs', 'slug_en' => 'article-en']);
        $this->get('/en/news/article-en')->assertOk();

        $notice = Notice::factory()->create(['slug_cs' => 'zprava-cs', 'slug_en' => 'notice-en']);
        $this->get('/en/committee-news/notice-en')->assertOk();
    }

    /**
     * Once app()->getLocale() is "en", spatie/laravel-translatable resolves a translatable
     * model attribute (e.g. Article::$title) straight to its English translation — no extra
     * plumbing needed on top of SetLocale (see that middleware's docblock).
     */
    public function test_translatable_model_attributes_switch_to_english_content(): void
    {
        $category = ArticleCategory::factory()->create();
        $article = Article::factory()->create([
            'article_category_id' => $category->id,
            'title' => ['cs' => 'Český titulek', 'en' => 'English headline'],
        ]);

        $response = $this->get(route('en.novinky.show', $article->slug_en));

        $response->assertOk();
        $response->assertSee('English headline');
        $response->assertDontSee('Český titulek');
    }
}
