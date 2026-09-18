<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * /faq has no other page-singleton editorial text (its heading is fixed copy, and the actual
 * Q&A content is FaqGroup/FaqItem) — this class exists purely to hold the page's SEO override
 * fields (see App\Support\Seo), same "{field}_en" nullable sibling CZ/EN pattern as every other
 * Settings class.
 */
class FaqSettings extends Settings
{
    public ?string $seo_title;

    public ?string $seo_title_en;

    public ?string $seo_description;

    public ?string $seo_description_en;

    public ?string $seo_image;

    public static function group(): string
    {
        return 'faq';
    }
}
