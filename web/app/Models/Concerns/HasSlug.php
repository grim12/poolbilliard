<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Auto-generates unique `slug_cs`/`slug_en` from the model's slug source field (see
 * slugSourceField()) on create, unless already given — needed for models whose ui/ prototype
 * has no real per-record routing (every detail page there is hardcoded to a single example).
 * Appends -2, -3... on collision, per locale column.
 *
 * The source field may be translatable (spatie HasTranslations — e.g. Article's `title`, which
 * returns an array via getTranslations()) or a plain string (e.g. Club/Herna's `name`, which
 * isn't translated — see skills/web-component-guide.md's "Dvojjazyčný obsah" section). Detected
 * per-field via isTranslatableAttribute() — a model can use HasTranslatableFormFields for OTHER
 * fields (e.g. Club's about_text) while its slug source field (name) stays plain, so we can't
 * just check whether the model has translation support at all.
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            $field = static::slugSourceField();

            if (method_exists($model, 'isTranslatableAttribute') && $model->isTranslatableAttribute($field)) {
                $cs = $model->getTranslations($field)['cs'] ?? '';
                $en = $model->getTranslations($field)['en'] ?? $cs;
            } else {
                $cs = $en = $model->{$field};
            }

            if (empty($model->slug_cs)) {
                $model->slug_cs = static::generateUniqueSlug('slug_cs', $cs);
            }

            if (empty($model->slug_en)) {
                $model->slug_en = static::generateUniqueSlug('slug_en', $en !== '' ? $en : $cs);
            }
        });
    }

    /**
     * The attribute slugs are generated from — override when the model's title field isn't
     * called `name` (e.g. Article/Notice use `title`).
     */
    protected static function slugSourceField(): string
    {
        return 'name';
    }

    protected static function generateUniqueSlug(string $column, string $text): string
    {
        $base = Str::slug($text);
        $slug = $base;
        $i = 2;

        while (static::where($column, $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
