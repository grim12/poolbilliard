<?php

namespace Tests\Feature;

use App\Enums\HernaStatus;
use App\Filament\Resources\Hernas\Pages\ListHernas;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Banner;
use App\Models\Club;
use App\Models\ClubMember;
use App\Models\CommitteeMember;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\FaqGroup;
use App\Models\FaqItem;
use App\Models\Herna;
use App\Models\JakZacitSection;
use App\Models\Leaderboard;
use App\Models\LinkTile;
use App\Models\Notice;
use App\Models\Partner;
use App\Models\RuleCard;
use App\Models\Tournament;
use App\Models\TournamentCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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
        Herna::factory()->create(['status' => HernaStatus::Pending]);
        $articleCategory = ArticleCategory::factory()->create();
        Article::factory()->create(['article_category_id' => $articleCategory->id]);
        Notice::factory()->create();
        Banner::factory()->create();
        Leaderboard::factory()->create();
        LinkTile::factory()->create();
        JakZacitSection::factory()->create();
        CommitteeMember::factory()->create();
        $documentCategory = DocumentCategory::factory()->create();
        Document::factory()->create(['document_category_id' => $documentCategory->id]);

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
            '/admin/committee-members',
            '/admin/document-categories',
            '/admin/documents',
            '/admin/manage-general-settings',
            '/admin/manage-homepage-settings',
            '/admin/manage-kluby-settings',
            '/admin/manage-herny-settings',
            '/admin/manage-kalendar-settings',
            '/admin/manage-souteze-settings',
            '/admin/manage-jak-zacit-settings',
            '/admin/manage-pravidla-settings',
            '/admin/manage-svaz-settings',
            '/admin/manage-partneri-settings',
            '/admin/manage-faq-settings',
            '/admin/manage-novinky-settings',
            '/admin/manage-vykonny-vybor-settings',
            '/admin/users',
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

    /**
     * Document's form combines a FileUpload with a ->relationship() Select that has a
     * ->createOptionForm() (add a new DocumentCategory inline) — worth its own create/edit
     * smoke test, same reasoning as Article's category select.
     */
    public function test_document_create_and_edit_pages_render(): void
    {
        $user = User::factory()->create();
        $category = DocumentCategory::factory()->create();
        $document = Document::factory()->create(['document_category_id' => $category->id]);

        $this->actingAs($user)->get('/admin/documents/create')->assertOk();
        $this->actingAs($user)->get("/admin/documents/{$document->id}/edit")->assertOk();
    }

    /**
     * LinkTile/RuleCard both gained the "Interní stránka"/"Konkrétní záznam" picker
     * (App\Filament\Support\InternalLinkFields) alongside their existing manual URL field —
     * worth their own create/edit smoke test, same reasoning as Banner's above.
     */
    public function test_link_tile_create_and_edit_pages_render(): void
    {
        $user = User::factory()->create();
        $tile = LinkTile::factory()->create();

        $this->actingAs($user)->get('/admin/link-tiles/create')->assertOk();
        $this->actingAs($user)->get("/admin/link-tiles/{$tile->id}/edit")->assertOk();
    }

    public function test_rule_card_create_and_edit_pages_render(): void
    {
        $user = User::factory()->create();
        $card = RuleCard::factory()->create();

        $this->actingAs($user)->get('/admin/rule-cards/create')->assertOk();
        $this->actingAs($user)->get("/admin/rule-cards/{$card->id}/edit")->assertOk();
    }

    /**
     * Club's form just gained a new "SEO" Section (seo_title/seo_description TranslatableTabs +
     * seo_image FileUpload) — worth its own create/edit smoke test, same reasoning as Banner's.
     */
    public function test_club_create_and_edit_pages_render(): void
    {
        $user = User::factory()->create();
        $club = Club::factory()->create();

        $this->actingAs($user)->get('/admin/clubs/create')->assertOk();
        $this->actingAs($user)->get("/admin/clubs/{$club->id}/edit")->assertOk();
    }

    /**
     * Tournament's form was entirely flat (no Section wrapper at all) until it gained its first
     * one for the new "SEO" fields — worth its own create/edit smoke test to confirm mixing a
     * Section into a previously flat schema doesn't break anything.
     */
    public function test_tournament_create_and_edit_pages_render(): void
    {
        $user = User::factory()->create();
        $category = TournamentCategory::factory()->create();
        $tournament = Tournament::factory()->create(['tournament_category_id' => $category->id]);

        $this->actingAs($user)->get('/admin/tournaments/create')->assertOk();
        $this->actingAs($user)->get("/admin/tournaments/{$tournament->id}/edit")->assertOk();
    }

    /**
     * "Schválit"/"Zamítnout" are the one-click moderation actions for a pending public
     * "Registrace herny" submission (see HernasTable) — worth testing the actions themselves,
     * not just that the index page renders.
     */
    public function test_herna_approve_and_reject_actions_update_status(): void
    {
        $user = User::factory()->create();
        $pending = Herna::factory()->create(['status' => HernaStatus::Pending]);

        Livewire::actingAs($user)
            ->test(ListHernas::class)
            ->callTableAction('approve', $pending);

        $this->assertSame(HernaStatus::Approved, $pending->refresh()->status);

        $anotherPending = Herna::factory()->create(['status' => HernaStatus::Pending]);

        Livewire::actingAs($user)
            ->test(ListHernas::class)
            ->callTableAction('reject', $anotherPending);

        $this->assertSame(HernaStatus::Rejected, $anotherPending->refresh()->status);
    }

    /**
     * No roles yet — every admin user has full access — but a user still must not be able to
     * delete their own account (UsersTable/EditUser hide the delete action for `auth()->user()`),
     * since that would lock them out of the admin with no way back in.
     */
    public function test_user_cannot_delete_their_own_account(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ListUsers::class)
            ->assertTableActionHidden('delete', $user)
            ->assertTableActionVisible('delete', $otherUser);

        Livewire::actingAs($user)
            ->test(EditUser::class, ['record' => $user->getRouteKey()])
            ->assertActionHidden('delete');
    }

    public function test_user_create_and_edit_pages_render(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user)->get('/admin/users/create')->assertOk();
        $this->actingAs($user)->get("/admin/users/{$otherUser->id}/edit")->assertOk();
    }

    /**
     * The dashboard's HernaStatsOverview widget — first widget in the project — surfaces the
     * "Registrace herny" moderation queue size and links straight into the already-filtered
     * Hernas list (Filament's `tableFilters` query-string deep link).
     */
    public function test_dashboard_shows_herna_moderation_stat(): void
    {
        $user = User::factory()->create();
        Herna::factory()->count(2)->create(['status' => HernaStatus::Pending]);
        Herna::factory()->create(['status' => HernaStatus::Approved]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
        $response->assertSee('Herny ke schválení');
        $response->assertSee('2');
    }
}
