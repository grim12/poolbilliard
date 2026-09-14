<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Auto-generates a unique `slug` from the model's slug source field (see slugSourceField()) on
 * create, unless one was already given — needed for models whose ui/ prototype has no real
 * per-record routing (every detail page there is hardcoded to a single example). Appends -2,
 * -3... on collision.
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->{static::slugSourceField()});
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

    protected static function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
