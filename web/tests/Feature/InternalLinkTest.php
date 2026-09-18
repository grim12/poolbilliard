<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\LinkTile;
use App\Models\RuleCard;
use App\Support\InternalLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternalLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_resolve_prefers_a_linkable_record_over_a_route_or_manual_url(): void
    {
        $club = Club::factory()->create();

        $this->assertSame(route('klub.show', $club), InternalLink::resolve('kluby', $club, 'https://example.com'));
    }

    public function test_resolve_prefers_a_route_over_a_manual_url(): void
    {
        $this->assertSame(route('kluby'), InternalLink::resolve('kluby', null, 'https://example.com'));
    }

    public function test_resolve_falls_back_to_the_manual_url_when_nothing_else_is_set(): void
    {
        $this->assertSame('https://example.com', InternalLink::resolve(null, null, 'https://example.com'));
    }

    /**
     * The whole point: the same stored pick (a route name or a record) resolves to the correct
     * locale's URL — no separate CZ/EN value to keep in sync.
     */
    public function test_resolve_follows_the_current_locale(): void
    {
        $club = Club::factory()->create();

        $this->assertSame(route('klub.show', $club), InternalLink::resolve(null, $club, null));

        app()->setLocale('en');

        $this->assertSame(route('en.klub.show', $club), InternalLink::resolve(null, $club, null));
    }

    public function test_resolve_from_array_loads_the_linkable_model_from_its_type_and_id(): void
    {
        $club = Club::factory()->create();

        $url = InternalLink::resolveFromArray([
            'linkable_type' => Club::class,
            'linkable_id' => $club->id,
            'link_route' => null,
            'url' => null,
        ]);

        $this->assertSame(route('klub.show', $club), $url);
    }

    public function test_link_tile_resolved_url_prefers_the_picked_route_over_its_manual_url(): void
    {
        $tile = LinkTile::factory()->create(['url' => 'https://example.com', 'link_route' => 'herny']);

        $this->assertSame(route('herny'), $tile->resolved_url);
    }

    public function test_rule_card_resolved_button_url_prefers_the_picked_record_over_its_manual_url(): void
    {
        $club = Club::factory()->create();
        $card = RuleCard::factory()->create([
            'button_url' => 'https://example.com',
            'linkable_type' => Club::class,
            'linkable_id' => $club->id,
        ]);

        $this->assertSame(route('klub.show', $club), $card->resolved_button_url);
    }
}
