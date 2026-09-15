<?php

namespace App\Http\Controllers;

use App\Enums\HernaStatus;
use App\Enums\Region;
use App\Models\Herna;
use Illuminate\View\View;

class HernaController extends Controller
{
    /**
     * "Herny" — mirrors ui/src/herny.njk. Only approved herny are public (HernaStatus::Pending/
     * Rejected are moderation states for a future public submission form — not built yet, see
     * skills/web-component-guide.md). Flat, ungrouped grid — unlike Kluby's directory, herny
     * aren't split by region (see ui/'s herna-list.njk doc comment).
     */
    public function index(): View
    {
        return view('herny', [
            'hernas' => Herna::where('status', HernaStatus::Approved)->orderBy('name')->get(),
            'regions' => Region::cases(),
        ]);
    }

    public function show(Herna $herna): View
    {
        return view('herna', [
            'herna' => $herna,
        ]);
    }
}
