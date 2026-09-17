<?php

namespace App\Http\Controllers;

use App\Enums\HernaStatus;
use App\Models\Article;
use App\Models\Club;
use App\Models\Herna;
use App\Models\Notice;
use App\Models\RecurringTournament;
use App\Models\Tournament;
use App\Support\Launch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SeoController extends Controller
{
    /**
     * Replaces the old static public/robots.txt — needs to be a real route so it can follow
     * App\Support\Launch::indexable() instead of always allowing everything.
     */
    public function robots(): Response
    {
        $body = Launch::indexable()
            ? "User-agent: *\nDisallow: /admin\nSitemap: ".route('sitemap')."\n"
            : "User-agent: *\nDisallow: /\n";

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }

    /**
     * Only the URLs a visitor (or crawler) can actually reach: no /admin, no pending Herna
     * submissions, no unpublished Article/Notice — same visibility rules their own controllers
     * already enforce. 404s while the site isn't indexable (see App\Support\Launch) instead of
     * publishing a list of URLs nothing should be crawling yet.
     */
    public function sitemap(): Response
    {
        abort_unless(Launch::indexable(), 404);

        $staticRoutes = [
            'home', 'kluby', 'herny', 'kalendar', 'souteze', 'jak-zacit',
            'sportovni-svaz', 'novinky', 'zpravodajstvi.vykonny-vybor', 'faq',
            'partneri', 'registrace-herny',
        ];

        $urls = collect($staticRoutes)->map(fn (string $name) => ['loc' => route($name)])
            ->concat(Club::all()->map(fn (Club $club) => ['loc' => route('klub.show', $club)]))
            ->concat(
                Herna::where('status', HernaStatus::Approved)->get()
                    ->map(fn (Herna $herna) => ['loc' => route('herna.show', $herna)])
            )
            ->concat(Tournament::all()->map(fn (Tournament $t) => ['loc' => route('turnaj.show', $t)]))
            ->concat(
                RecurringTournament::all()
                    ->map(fn (RecurringTournament $t) => ['loc' => route('pravidelny-turnaj.show', $t)])
            )
            ->concat($this->publishedUrls(Article::query(), 'novinky.show'))
            ->concat($this->publishedUrls(Notice::query(), 'zpravodajstvi.vykonny-vybor.show'));

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * @return Collection<int, array{loc: string, lastmod: ?string}>
     */
    private function publishedUrls(Builder $query, string $routeName): Collection
    {
        return $query->whereNotNull('published_at')->get()
            ->map(fn ($model) => [
                'loc' => route($routeName, $model->slug_cs),
                'lastmod' => $model->published_at?->toAtomString(),
            ]);
    }
}
