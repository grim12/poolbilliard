<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Leaderboard;
use App\Models\LinkTile;
use App\Models\Notice;
use App\Models\Partner;
use App\Models\Tournament;
use App\Settings\GeneralSettings;
use App\Settings\HomepageSettings;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Mirrors ui/src/index.njk. Every section's content is either a real query (with a fixed
     * count baked in, not admin-configurable — the grid layouts are built for a specific number
     * of cards) or a HomepageSettings pick, see skills/web-component-guide.md.
     */
    public function index(HomepageSettings $settings, GeneralSettings $generalSettings): View
    {
        $latestArticles = Article::with('category')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        return view('home', [
            'featuredArticle' => $latestArticles->first(),
            'featuredArticleItems' => $latestArticles->slice(1),
            'notices' => Notice::whereNotNull('published_at')->orderByDesc('published_at')->take(3)->get(),
            'banner1' => $settings->banner_1_id ? Banner::find($settings->banner_1_id) : null,
            'tournaments' => Tournament::with('category')->currentAndUpcoming()->orderedByStartDate()->take(4)->get(),
            'banner2' => $settings->banner_2_id ? Banner::find($settings->banner_2_id) : null,
            'leaderboards' => Leaderboard::orderBy('sort_order')->get(),
            'linkTiles' => LinkTile::whereIn('id', $settings->link_tile_ids)
                ->get()
                ->sortBy(fn (LinkTile $tile) => array_search($tile->id, $settings->link_tile_ids))
                ->values(),
            'partners' => Partner::orderBy('sort_order')->get(),
            'settings' => $settings,
            'cmbsTvUrl' => $generalSettings->cmbs_tv_url,
        ]);
    }
}
