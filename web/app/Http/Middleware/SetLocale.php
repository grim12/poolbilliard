<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applied only to the /en route group (see routes/web.php's `Route::name('en.')->group(...)`)
 * — the /cs (default) group needs nothing, since app.locale is already "cs". Sets the locale
 * for the whole request lifecycle: translatable model attributes (spatie/laravel-translatable)
 * and __()/trans() calls all read app()->getLocale() themselves once this runs.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next, string $locale): Response
    {
        app()->setLocale($locale);

        return $next($request);
    }
}
