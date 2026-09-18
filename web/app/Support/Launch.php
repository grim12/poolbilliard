<?php

namespace App\Support;

/**
 * Central "is the site actually live yet" checks, used by SiteLock middleware and the public
 * layout's meta robots tag / robots.txt. Both default to off (locked / noindex) everywhere
 * except local dev and the test suite, and both can be overridden independently via their own
 * .env flag for the rare case they need to move separately (e.g. a soft-launch: unlocked for
 * visitors but still noindex for a while).
 */
class Launch
{
    public static function siteLocked(): bool
    {
        return config('sitelock.enabled') ?? ! app()->environment('local', 'testing');
    }

    public static function indexable(): bool
    {
        return config('seo.indexable') ?? ! self::siteLocked();
    }
}
