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

    public static function group(): string
    {
        return 'general';
    }
}
