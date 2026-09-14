<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\View\View;

class TournamentController extends Controller
{
    /**
     * Standalone preview of the tournaments() teaser widget with real data — ui/ doesn't have
     * a dedicated route for this yet (there it's only a homepage section + the full Kalendář
     * page, which also needs its interactive calendar/filters ported separately).
     */
    public function index(): View
    {
        return view('turnaje', [
            'tournaments' => Tournament::with('category')->currentAndUpcoming()->orderBy('sort_order')->get(),
        ]);
    }
}
