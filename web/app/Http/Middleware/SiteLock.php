<?php

namespace App\Http\Middleware;

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
        if (! $this->isLocked() || $request->is('admin*', 'up') || $request->routeIs('site-lock.*') || Auth::check()) {
            return $next($request);
        }

        return redirect()->guest(route('site-lock.show'));
    }

    private function isLocked(): bool
    {
        $override = config('sitelock.enabled');

        if ($override !== null) {
            return $override;
        }

        return ! app()->environment('local', 'testing');
    }
}
