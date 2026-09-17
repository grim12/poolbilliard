<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\TranslationLoader\LanguageLine;
use Tests\TestCase;

/**
 * Spatie\TranslationLoader\LanguageLine replaced lang/en.json (see
 * App\Filament\Resources\Translations\TranslationResource's docblock) — RefreshDatabase gives
 * every test an empty `language_lines` table, so a plain __() call with no matching row just
 * falls back to its Czech key, same as any other untranslated string. These tests seed one row
 * explicitly to cover the DB-loader wiring itself, since nothing else in the suite does.
 */
class TranslationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_seeded_language_line_translates_the_english_locale(): void
    {
        LanguageLine::create([
            'group' => '*',
            'key' => 'Testovací klíč',
            'text' => ['en' => 'Test key'],
        ]);

        app()->setLocale('en');

        $this->assertSame('Test key', __('Testovací klíč'));
    }

    public function test_an_untranslated_key_falls_back_to_itself(): void
    {
        app()->setLocale('en');

        $this->assertSame('Nepřeložený klíč', __('Nepřeložený klíč'));
    }

    public function test_translations_admin_pages_render(): void
    {
        $user = User::factory()->create();
        $line = LanguageLine::create([
            'group' => '*',
            'key' => 'Testovací klíč',
            'text' => ['en' => 'Test key'],
        ]);

        $this->actingAs($user)->get('/admin/translations')->assertOk();
        $this->actingAs($user)->get('/admin/translations/create')->assertOk();
        $this->actingAs($user)->get("/admin/translations/{$line->id}/edit")->assertOk();
    }
}
