<?php

namespace App\Http\Controllers;

use App\Models\RecurringTournament;
use Illuminate\View\View;

class RecurringTournamentController extends Controller
{
    /**
     * Mirrors ui/src/pravidelny-turnaj.njk — reuses the same <x-tournament-content> component
     * as Tournament's own detail page (TournamentController::show()), since the two share the
     * exact same "tag + title + date/location meta + freeform body" shape; only the backing
     * model and the optional Herna "Odkazy" link (see resources/views/pravidelny-turnaj.blade.php)
     * differ.
     */
    public function show(RecurringTournament $recurringTournament): View
    {
        $recurringTournament->load('herna');

        return view('pravidelny-turnaj', [
            'tournament' => $recurringTournament,
        ]);
    }
}
