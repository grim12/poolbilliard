<?php

namespace App\Filament\Support;

use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * Wraps a translatable model field in a CZ/EN Tabs component — see skills/web-component-guide.md's
 * "Dvojjazyčný obsah" section for the full pattern (model side: App\Models\Concerns\
 * HasTranslatableFormFields, which wraps spatie/laravel-translatable and exposes each
 * `$translatable` field as a virtual `{field}_translations` attribute).
 *
 * $component receives the locale ('cs'/'en') and must return a fresh field instance (any `make()`
 * name — its statePath gets pointed at the real `{field}_translations.{locale}` key here via
 * ->statePath(), not ->name() — ->name() only changes the field's *label/id*, not what it's
 * actually bound to, which silently left every field bound to its own literal ::make() argument
 * instead), so the caller stays in control of per-locale concerns like `->required($locale ===
 * 'cs')`.
 *
 * We built this instead of the official spatie-laravel-translatable-plugin because that plugin
 * doesn't support Filament v5 yet (requires filament/support v3.x). The DB/model layer (JSON
 * column, HasTranslations, $translatable) is deliberately identical to what the plugin expects,
 * so swapping this helper out for the real plugin later needs no data migration.
 */
class TranslatableTabs
{
    public static function make(string $field, Closure $component, ?string $label = null): Tabs
    {
        return Tabs::make($field)
            ->label($label)
            ->tabs([
                Tab::make('cs')
                    ->label('Čeština')
                    ->schema([$component('cs')->statePath("{$field}_translations.cs")]),
                Tab::make('en')
                    ->label('English')
                    ->schema([$component('en')->statePath("{$field}_translations.en")]),
            ]);
    }
}
