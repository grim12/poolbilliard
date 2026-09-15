<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Settings\KlubyHernySettings;
use Illuminate\View\View;

class ClubController extends Controller
{
    /**
     * "Kluby" — mirrors ui/src/kluby.njk + klub.njk. Grouped by region for the directory
     * listing (ui/'s clubDirectory() macro groups a flat mock array via Nunjucks' groupby();
     * here it's a real Eloquent collection, so the grouping happens here instead of in the
     * Blade component). `region` is enum-cast (App\Enums\Region), so grouping keys on
     * `region->value` — grouping directly on the enum instance would throw ("Illegal offset
     * type"), since PHP array/collection keys can't be objects.
     */
    public function index(KlubyHernySettings $settings): View
    {
        $clubs = Club::orderBy('region')->orderBy('name')->get()
            ->groupBy(fn (Club $club) => $club->region?->value);

        return view('kluby', [
            'clubs' => $clubs,
            'settings' => $settings,
        ]);
    }

    /**
     * Single club — mirrors ui/src/klub.njk. `members`/`recruitment_message` are already real
     * relations/accessors on the model (Club::members(), Club::recruitmentMessage()), so no
     * extra query shaping needed here beyond eager-loading members.
     */
    public function show(Club $club): View
    {
        $club->load('members');

        return view('klub', [
            'club' => $club,
        ]);
    }
}
