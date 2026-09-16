<?php

namespace App\Http\Controllers;

use App\Enums\HernaStatus;
use App\Enums\Region;
use App\Models\Herna;
use App\Settings\HernySettings;
use Illuminate\View\View;

class HernaController extends Controller
{
    /**
     * "Herny" — mirrors ui/src/herny.njk. Only approved herny are public (HernaStatus::Pending/
     * Rejected are moderation states for the public submission form, see
     * RegistraceHernyController). Flat, ungrouped grid — unlike Kluby's directory, herny
     * aren't split by region (see ui/'s herna-list.njk doc comment).
     */
    public function index(HernySettings $settings): View
    {
        return view('herny', [
            'hernas' => Herna::where('status', HernaStatus::Approved)->orderBy('name')->get(),
            'regions' => Region::cases(),
            'settings' => $settings,
        ]);
    }

    public function show(Herna $herna): View
    {
        abort_unless($herna->status === HernaStatus::Approved, 404);

        return view('herna', [
            'herna' => $herna,
        ]);
    }
}
