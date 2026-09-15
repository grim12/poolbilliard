<?php

namespace App\Filament\Support;

use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * One shared CZ/EN Tabs component for every translatable field on a resource's form — one
 * toggle for the whole content item, not one per field (that read as confusing/repetitive:
 * see skills/web-component-guide.md's "Dvojjazyčný obsah" section for the full pattern; model
 * side is App\Models\Concerns\HasTranslatableFormFields, which wraps spatie/laravel-translatable
 * and exposes each `$translatable` field as a virtual `{field}_translations` attribute).
 *
 * $fields maps field name => closure(locale): a fresh field instance (any `make()` name — its
 * statePath gets pointed at the real `{field}_translations.{locale}` key here via ->statePath(),
 * not ->name() — ->name() only changes the field's *label/id*, not what it's actually bound to).
 * The closure stays in control of per-locale concerns like `->required($locale === 'cs')`.
 *
 * Non-translatable fields (image, slug, relations, dates...) stay outside this component,
 * wherever they made sense in the form — only translatable fields need to live inside it, and
 * all of them for a given resource belong in the *same* TranslatableTabs::make() call so there's
 * one language switch per record, not one per field.
 *
 * We built this instead of the official spatie-laravel-translatable-plugin because that plugin
 * doesn't support Filament v5 yet (requires filament/support v3.x). The DB/model layer (JSON
 * column, HasTranslations, $translatable) is deliberately identical to what the plugin expects,
 * so swapping this helper out for the real plugin later needs no data migration.
 */
class TranslatableTabs
{
    /**
     * @param  array<string, Closure>  $fields  field name => closure(string $locale): Component
     */
    public static function make(array $fields, ?string $label = null): Tabs
    {
        return Tabs::make('translations')
            ->label($label)
            ->tabs([
                self::tab('cs', 'Čeština', $fields),
                self::tab('en', 'English', $fields),
            ]);
    }

    /**
     * @param  array<string, Closure>  $fields
     */
    protected static function tab(string $locale, string $label, array $fields): Tab
    {
        return Tab::make($locale)
            ->label($label)
            ->schema(
                collect($fields)
                    ->map(fn (Closure $component, string $field) => $component($locale)->statePath("{$field}_translations.{$locale}"))
                    ->values()
                    ->all()
            );
    }
}
