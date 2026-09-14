<?php

namespace App\Providers;

use Illuminate\Foundation\DevCommands;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
