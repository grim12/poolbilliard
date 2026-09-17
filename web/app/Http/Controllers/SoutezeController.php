<?php

namespace App\Http\Controllers;

use App\Models\CompetitionSection;
use App\Models\Leaderboard;
use App\Settings\SoutezeSettings;
use Illuminate\View\View;

class SoutezeController extends Controller
{
    /**
     * Mirrors ui/src/souteze.njk. Explicitly a "hub" page for now (see
     * skills/web-component-guide.md) — the numbered sections are CompetitionSection records,
     * not yet a real per-competition entity/page (that's expected future work). Jump-nav items
     * are derived from the sections themselves (anchor + nav_label) plus one static "Žebříčky"
     * pill for the leaderboards section below them, same order as ui/'s hardcoded jumpNav() call.
     */
    public function index(SoutezeSettings $settings): View
    {
        $sections = CompetitionSection::orderBy('sort_order')->get();

        return view('souteze', [
            'settings' => $settings,
            'sections' => $sections,
            'jumpNavItems' => [
                ...$sections->map(fn (CompetitionSection $section) => [
                    'text' => $section->nav_label,
                    'url' => '#'.$section->anchor,
                ])->all(),
                ['text' => __('Žebříčky'), 'url' => '#zebricky'],
            ],
            'leaderboards' => Leaderboard::orderBy('sort_order')->get(),
        ]);
    }
}
