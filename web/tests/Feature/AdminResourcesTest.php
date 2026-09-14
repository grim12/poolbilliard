<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Banner;
use App\Models\Club;
use App\Models\ClubMember;
use App\Models\FaqGroup;
use App\Models\FaqItem;
use App\Models\Herna;
use App\Models\Notice;
use App\Models\Partner;
use App\Models\Tournament;
use App\Models\TournamentCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResourcesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Smoke test: each Filament resource's index page (plus the settings page) renders for an
     * authenticated user without a Blade/Livewire error, with one real record of each type
     * present so relationship/enum columns actually render, not just an empty table.
     */
    public function test_admin_resource_index_pages_render(): void
    {
        $user = User::factory()->create();

        Partner::factory()->create();
        FaqItem::factory()->create();
        FaqGroup::factory()->create();
        $category = TournamentCategory::factory()->create();
        Tournament::factory()->create(['tournament_category_id' => $category->id]);
        Club::factory()->has(ClubMember::factory()->count(2), 'members')->create();
        Herna::factory()->create();
        $articleCategory = ArticleCategory::factory()->create();
        Article::factory()->create(['article_category_id' => $articleCategory->id]);
        Notice::factory()->create();
        Banner::factory()->create();

        $urls = [
            '/admin/partners',
            '/admin/faq-items',
            '/admin/faq-groups',
            '/admin/tournament-categories',
            '/admin/tournaments',
            '/admin/clubs',
            '/admin/hernas',
            '/admin/article-categories',
            '/admin/articles',
            '/admin/notices',
            '/admin/banners',
            '/admin/manage-general-settings',
        ];

        foreach ($urls as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }
}
