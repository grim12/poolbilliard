<?php

namespace App\Http\Middleware;

use App\Support\Launch;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates every public page behind a login using the same `users` table as the Filament admin
 * (see SiteLockController) — the whole site isn't ready for visitors/search engines yet.
 * Locked by default anywhere except local dev and the test suite; `SITE_LOCK_ENABLED` in
 * .env overrides that (set it to `false` once the site is ready to go live).
 */
class SiteLock
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            ! Launch::siteLocked()
            || $request->is('admin*', 'up', 'robots.txt', 'sitemap.xml', 'system/deploy-runner')
            || $request->routeIs('site-lock.*')
            || Auth::check()
        ) {
            return $next($request);
        }

        return redirect()->guest(route('site-lock.show'));
    }
}
