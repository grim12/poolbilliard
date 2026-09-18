<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteLockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The test suite runs as APP_ENV=testing, which SiteLock treats as unlocked by default (see
     * its docblock) — every test here forces the lock on via config to exercise it explicitly.
     */
    private function lockSite(): void
    {
        config(['sitelock.enabled' => true]);
    }

    public function test_guest_is_redirected_to_the_unlock_screen_when_the_site_is_locked(): void
    {
        $this->lockSite();

        $this->get('/')->assertRedirect(route('site-lock.show'));
    }

    public function test_admin_panel_stays_reachable_even_when_the_site_is_locked(): void
    {
        $this->lockSite();

        $this->get('/admin/login')->assertOk();
    }

    public function test_site_stays_open_when_the_lock_is_not_enabled(): void
    {
        config(['sitelock.enabled' => false]);

        $this->get('/')->assertOk();
    }

    public function test_valid_admin_credentials_unlock_the_site_and_redirect_to_the_intended_page(): void
    {
        $this->lockSite();
        $user = User::factory()->create(['password' => bcrypt('correct-password')]);

        $this->get('/kluby')->assertRedirect(route('site-lock.show'));

        $response = $this->from(route('site-lock.show'))->post(route('site-lock.attempt'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ]);

        $response->assertRedirect('/kluby');
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $this->lockSite();
        $user = User::factory()->create(['password' => bcrypt('correct-password')]);

        $response = $this->post(route('site-lock.attempt'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
