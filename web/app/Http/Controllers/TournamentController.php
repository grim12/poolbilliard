<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\View\View;

class TournamentController extends Controller
{
    /**
     * Single tournament — mirrors ui/src/turnaj.njk + pravidelny-turnaj.njk, which are two
     * hardcoded example pages sharing the same tournamentContent() widget (one-off "Svazový"
     * tournament vs. recurring "Pravidelný" one) rather than a real per-record detail page —
     * same situation Club/Herna were in before HasSlug. `description` is the freeform
     * RichEditor body (rules/startovné/links) that ui/'s mock hardcoded per page; `url` is kept
     * as the external CTA link (registration/results service), not the internal route — the
     * card grid links here via slug_cs instead (see components/tournaments.blade.php).
     */
    public function show(Tournament $tournament): View
    {
        $tournament->load('category');

        return view('turnaj', [
            'tournament' => $tournament,
        ]);
    }
}
