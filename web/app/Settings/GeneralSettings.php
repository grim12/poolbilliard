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
     * Club::recruitmentMessage().
     */
    public string $recruitment_open_fallback_text;

    public string $recruitment_closed_fallback_text;

    /**
     * Odkaz na živé přenosy z turnajů (ČMBS TV) — zobrazený v poznámce pod sekcí Turnaje na
     * homepage. Globální fakt, ne obsah jedné konkrétní stránky, proto tady a ne v
     * HomepageSettings — kdyby se stejná poznámka objevila i jinde, je hned k dispozici.
     */
    public string $cmbs_tv_url;

    public static function group(): string
    {
        return 'general';
    }
}
