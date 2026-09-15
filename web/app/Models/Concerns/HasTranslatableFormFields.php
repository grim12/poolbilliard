<?php

namespace App\Models\Concerns;

use Spatie\Translatable\HasTranslations;

/**
 * Drop-in replacement for spatie/laravel-translatable's `HasTranslations` (pulls it in and
 * keeps `public array $translatable = [...]`, `getTranslation()`, etc. — nothing changes there)
 * that additionally exposes each translatable field as a virtual `{field}_translations`
 * attribute — e.g. `title` → `title_translations`, returning/accepting
 * `['cs' => ..., 'en' => ...]` (always both keys — see getAttribute() below for why).
 *
 * Add `{field}_translations` to `$fillable` and Filament's Create/Edit pages can mass-assign it
 * like any real column (see App\Filament\Support\TranslatableTabs and
 * skills/web-component-guide.md's "Dvojjazyčný obsah" section) — no per-field accessor method
 * needed, and no custom Filament hydrate/dehydrate hooks either.
 *
 * Two things had to be worked around to make this actually load on Filament's Edit pages:
 *
 * 1. Filament's `EditRecord::fillForm()` hydrates from `$record->attributesToArray()`, not from
 *    live attribute access — and `attributesToArray()` only includes real columns/casts/$appends
 *    (via Eloquent's mutator-lookup machinery, which requires an actual `{field}Translations()`
 *    method to exist — a plain `getAttribute()` override doesn't satisfy it). Rather than add
 *    one such method per translatable field per model, `attributesToArray()` is overridden
 *    directly below to inject `{field}_translations` for every `$translatable` field — same
 *    values `getAttribute()` already computes correctly, just also copied into the array Filament
 *    actually reads from.
 * 2. A genuinely-untranslated locale (e.g. `en` before anyone's filled it in) must resolve to
 *    `null`, not `''` — `RichEditor`'s Tiptap-based state cast only has a `null`-safe fallback
 *    (`$state ?? [default doc]`); an empty *string* skips that fallback and gets handed straight
 *    to `ueberdosis/tiptap-php`, which throws trying to parse `''` as HTML
 *    (`DOMParser::getDocumentBody(): Return value must be of type DOMElement, null returned`).
 *
 * `getAttribute()` is overridden (not `getAttributeValue()`) because Eloquent's `getAttribute()`
 * only calls `getAttributeValue()` for keys that already exist as a real column/cast/mutator —
 * `{field}_translations` is a virtual key with none of those, so it would short-circuit to the
 * relation-lookup path (and silently return null) without this. `setAttribute()` doesn't need
 * that treatment — Eloquent's mass-assignment (`fill()`) calls it directly for every fillable
 * key regardless. It still needs the `as` alias below though, since both this trait and
 * HasTranslations define it, and we need to fall back to HasTranslations' version for the
 * non-"_translations" case (real translatable columns, e.g. plain `$model->title = '...'`).
 */
trait HasTranslatableFormFields
{
    use HasTranslations {
        HasTranslations::setAttribute as private setTranslatableAttribute;
    }

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes["{$field}_translations"] = $this->getAttribute("{$field}_translations");
        }

        return $attributes;
    }

    public function getAttribute($key)
    {
        if ($field = $this->translatableFieldFromFormKey($key)) {
            return [
                'cs' => $this->getTranslation($field, 'cs', useFallbackLocale: false),
                'en' => $this->getTranslation($field, 'en', useFallbackLocale: false),
            ];
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if ($field = $this->translatableFieldFromFormKey($key)) {
            return $this->setTranslations($field, array_filter((array) $value, fn ($v) => filled($v)));
        }

        return $this->setTranslatableAttribute($key, $value);
    }

    protected function translatableFieldFromFormKey(string $key): ?string
    {
        if (! str_ends_with($key, '_translations')) {
            return null;
        }

        $field = substr($key, 0, -strlen('_translations'));

        return $this->isTranslatableAttribute($field) ? $field : null;
    }

    /**
     * `updateOrCreate([$field => $value], $attributes)` for a translatable $field — plain
     * `updateOrCreate` compiles the lookup into `WHERE $field = $value`, which runs against the
     * raw JSON blob (`{"cs":"..."}`) and never matches, so every reseed would insert duplicates
     * instead of updating. Used by seeders (see database/seeders/*Seeder.php) that key content
     * off a translatable field like `title`/`name`/`question` — matches on the CZ translation,
     * since seed content is always authored in Czech first.
     */
    public static function updateOrCreateByTranslation(string $field, string $value, array $attributes = [], string $locale = 'cs'): static
    {
        $existing = static::query()->whereJsonContainsLocale($field, $locale, $value)->first();

        if ($existing) {
            $existing->update($attributes);

            return $existing;
        }

        return static::create([$field => $value, ...$attributes]);
    }
}
