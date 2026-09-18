<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * /partneri has no other page-singleton editorial text (its hero title/description are fixed
 * copy, and the partner grid itself is the Partner model/resource) — this class exists purely
 * to hold the page's SEO override fields (see App\Support\Seo), same "{field}_en" nullable
 * sibling CZ/EN pattern as every other Settings class.
 */
class PartneriSettings extends Settings
{
    public ?string $seo_title;

    public ?string $seo_title_en;

    public ?string $seo_description;

    public ?string $seo_description_en;

    public ?string $seo_image;

    public static function group(): string
    {
        return 'partneri';
    }
}
