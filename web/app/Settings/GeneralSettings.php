<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    /**
     * Kolik dní dopředu se turnaj považuje za "blížící se" (zvýrazněné datum na kartě
     * turnaje — viz Tournament::isSoon()).
     */
    public int $tournament_soon_threshold_days;

    /**
     * Fallback rich text shown on a club's recruitment notice when the club's own
     * recruitment_text is empty — one for each Club::$recruitment_open state. See
     * Club::recruitmentMessage(). `_en` siblings hold the English translation — see
     * skills/web-component-guide.md's "Dvojjazyčný obsah" section.
     */
    public string $recruitment_open_fallback_text;

    public ?string $recruitment_open_fallback_text_en;

    public string $recruitment_closed_fallback_text;

    public ?string $recruitment_closed_fallback_text_en;

    /**
     * Odkaz na živé přenosy z turnajů (ČMBS TV) — zobrazený v poznámce pod sekcí Turnaje na
     * homepage. Globální fakt, ne obsah jedné konkrétní stránky, proto tady a ne v
     * HomepageSettings — kdyby se stejná poznámka objevila i jinde, je hned k dispozici.
     */
    public string $cmbs_tv_url;

    /**
     * Sitewide chrome toggle for <x-layouts.app>'s header — light (false, the default/primary
     * look) or dark (true, ui/'s "t-dark" variant, see demo-dark-header.njk). A page can still
     * force one or the other via <x-layouts.app :header-dark="...">, but no page does that
     * today — this setting is the only thing that decides it in practice.
     */
    public bool $header_dark;

    /**
     * Appended to every page's <title> as " — {suffix}" (see <x-layouts.app>'s `titleSuffix`
     * prop) — centralizes what used to be a literal " — Poolbilliard" hardcoded into every
     * page's own blade file. Also doubles as `og:site_name`, since both are the same "brand
     * name shown after the page's own title" concept. The homepage is the one page that opts
     * out (`:title-suffix="false"`) — its own title already spells out the full brand name.
     */
    public string $seo_title_suffix;

    /**
     * Sitewide fallback `og:image`/`twitter:image` — used by <x-layouts.app> only when neither
     * the current page nor entity has its own `seo_image` (or other content image) set. Stores
     * a relative disk path, same convention as every other image field — see App\Support\Seo.
     */
    public ?string $seo_default_og_image;

    public static function group(): string
    {
        return 'general';
    }
}
