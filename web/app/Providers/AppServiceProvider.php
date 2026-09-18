<?php

namespace App\Providers;

use Illuminate\Foundation\DevCommands;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /**
         * Mirrors ui/'s .eleventy.js "initials" Nunjucks filter — badge label for herna cards
         * (e.g. "Billiard Club Harlequin Praha" -> "BCH"). `Str::initials($herna->name)` in Blade.
         */
        Str::macro('initials', function (string $name, int $max = 3): string {
            return collect(preg_split('/\s+/', trim($name)))
                ->filter()
                ->take($max)
                ->map(fn (string $word) => mb_strtoupper(mb_substr($word, 0, 1)))
                ->implode('');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force every URL Laravel generates (route()/url()/asset()) onto https:// in
        // production, so nothing links back to a plain http:// URL even if the request
        // itself came in over HTTP. This does NOT redirect an incoming HTTP request itself —
        // that needs a real HTTP->HTTPS redirect at the webserver/load-balancer level, plus
        // (if there's a proxy in front of the app) trusted proxies configured so
        // Request::secure() reads the real scheme instead of the proxy's local HTTP hop.
        // Deliberately left for whoever sets up production hosting, since getting trusted
        // proxies wrong here risks a redirect loop.
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // `composer run dev`/`php artisan dev`'s default `serve` entry auto-restarts on file
        // changes (--reload), which is incompatible with PHP_CLI_SERVER_WORKERS — the server
        // silently falls back to a single worker, so Livewire's own async requests (e.g.
        // FileUpload fetching an uploaded image's size for its preview) can queue behind
        // another open connection and hang forever. This registers with the (higher-priority)
        // userland call, overriding the default's --reload'd `serve`, so `composer run dev`
        // gets working concurrency without needing everyone to remember `--no-reload` by hand.
        DevCommands::artisan('serve --no-reload', 'server');
    }
}
