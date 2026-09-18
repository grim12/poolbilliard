<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Shared helper for the admin-editable SEO fields (seo_title/seo_description/seo_image) added
 * to every entity with its own detail page and every page-backing Settings class — see each
 * model's/Settings class's own `seo_image` property doc for the field set itself.
 */
class Seo
{
    /**
     * `seo_image` (and the model-side equivalents like Club::$image) only ever store the
     * relative disk path — same reasoning as Club::imageUrl()/Article::imageUrl().
     */
    public static function imageUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
