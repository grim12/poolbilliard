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
use App\Models\JakZacitSection;
use App\Models\Leaderboard;
use App\Models\LinkTile;
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
        Leaderboard::factory()->create();
        LinkTile::factory()->create();
        JakZacitSection::factory()->create();

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
            '/admin/leaderboards',
            '/admin/link-tiles',
            '/admin/jak-zacit-sections',
            '/admin/manage-general-settings',
            '/admin/manage-homepage-settings',
            '/admin/manage-jak-zacit-settings',
        ];

        foreach ($urls as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }

    /**
     * Banner's form has the newest/most fragile field types in the admin (a Repeater storing a
     * JSON array of buttons, RichEditor, an enum Select) — worth its own create/edit smoke test
     * beyond the plain index-page check above.
     */
    public function test_banner_create_and_edit_pages_render(): void
    {
        $user = User::factory()->create();
        $banner = Banner::factory()->create();

        $this->actingAs($user)->get('/admin/banners/create')->assertOk();
        $this->actingAs($user)->get("/admin/banners/{$banner->id}/edit")->assertOk();
    }

    /**
     * Leaderboard's `entries` Repeater is reorderable (order = ranking), unlike Banner's fixed
     * order — worth its own create/edit smoke test too.
     */
    public function test_leaderboard_create_and_edit_pages_render(): void
    {
        $user = User::factory()->create();
        $leaderboard = Leaderboard::factory()->create();

        $this->actingAs($user)->get('/admin/leaderboards/create')->assertOk();
        $this->actingAs($user)->get("/admin/leaderboards/{$leaderboard->id}/edit")->assertOk();
    }

    /**
     * JakZacitSection's `steps` Repeater nests a RichEditor per row (unlike Banner's plain
     * TextInput rows) — worth its own create/edit smoke test.
     */
    public function test_jak_zacit_section_create_and_edit_pages_render(): void
    {
        $user = User::factory()->create();
        $section = JakZacitSection::factory()->create();

        $this->actingAs($user)->get('/admin/jak-zacit-sections/create')->assertOk();
        $this->actingAs($user)->get("/admin/jak-zacit-sections/{$section->id}/edit")->assertOk();
    }
}
