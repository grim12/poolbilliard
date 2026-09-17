<?php

namespace Tests\Feature;

use App\Enums\HernaStatus;
use App\Models\Herna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_route_renders_the_branded_404_page(): void
    {
        $response = $this->get('/tato-stranka-neexistuje');

        $response->assertNotFound();
        $response->assertSee('Stránka nenalezena');
    }

    /**
     * Regression coverage for HernaController::show()'s abort_unless — a pending/rejected
     * herna 404s with the same branded page as any other unmatched URL, not the framework
     * default, and never leaks the herna's name/content.
     */
    public function test_pending_herna_renders_the_branded_404_page_instead_of_leaking_its_content(): void
    {
        $herna = Herna::factory()->create(['status' => HernaStatus::Pending, 'name' => 'Neschválená herna']);

        $response = $this->get(route('herna.show', $herna));

        $response->assertNotFound();
        $response->assertSee('Stránka nenalezena');
        $response->assertDontSee('Neschválená herna');
    }

    /**
     * The 500 view deliberately skips <x-layouts.app> (see its own docblock) — this just checks
     * it still compiles and renders on its own, since nothing else in the suite ever hits it.
     */
    public function test_500_error_view_renders_on_its_own(): void
    {
        $html = view('errors.500')->render();

        $this->assertStringContainsString('Chyba serveru', $html);
    }
}
