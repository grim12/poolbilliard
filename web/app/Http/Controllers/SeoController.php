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
     * already enforce. 404s while the site isn't indexable (see App\Support\Launch). Lists both
     * the cs and en (see routes/web.php's 'en.' mirror) version of every URL — passing a model
     * straight to route() picks up whichever slug column that specific route's binding uses
     * ({club:slug_cs} vs. {club:slug_en}), so the same code covers both locales.
     */
    public function sitemap(): Response
    {
        abort_unless(Launch::indexable(), 404);

        $staticRoutes = [
            'home', 'kluby', 'herny', 'kalendar', 'souteze', 'jak-zacit',
            'sportovni-svaz', 'novinky', 'zpravodajstvi.vykonny-vybor', 'faq',
            'partneri', 'registrace-herny',
        ];

        $approvedHernas = Herna::where('status', HernaStatus::Approved)->get();
        $clubs = Club::all();
        $tournaments = Tournament::all();
        $recurringTournaments = RecurringTournament::all();

        $urls = collect();

        foreach (['', 'en.'] as $prefix) {
            $urls = $urls
                ->concat(collect($staticRoutes)->map(fn (string $name) => ['loc' => route($prefix.$name)]))
                ->concat($clubs->map(fn (Club $club) => ['loc' => route($prefix.'klub.show', $club)]))
                ->concat($approvedHernas->map(fn (Herna $herna) => ['loc' => route($prefix.'herna.show', $herna)]))
                ->concat($tournaments->map(fn (Tournament $t) => ['loc' => route($prefix.'turnaj.show', $t)]))
                ->concat($recurringTournaments->map(fn (RecurringTournament $t) => ['loc' => route($prefix.'pravidelny-turnaj.show', $t)]))
                ->concat($this->publishedUrls(Article::query(), $prefix.'novinky.show'))
                ->concat($this->publishedUrls(Notice::query(), $prefix.'zpravodajstvi.vykonny-vybor.show'));
        }

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
                'loc' => route($routeName, $model),
                'lastmod' => $model->published_at?->toAtomString(),
            ]);
    }
}
