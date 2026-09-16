<?php

namespace App\Http\Controllers;

use App\Models\JakZacitSection;
use App\Settings\JakZacitSettings;
use Illuminate\View\View;

class JakZacitController extends Controller
{
    /**
     * Mirrors ui/src/jak-zacit.njk. $settings holds the hero, the 4 "Kde začít" feature cards,
     * and the closing CTA/match-form headers; $sections are the 3 audience-path
     * JakZacitSection records, each carrying its own FAQ items (see
     * JakZacitSection::faqItems()). The jump-nav's trailing "Chci se zlepšit" pill (pointing at
     * the match-form section below the audience paths) is a fixed 4th item, same as ui/'s
     * hardcoded jumpNav() call — it isn't derived from a section.
     */
    public function index(JakZacitSettings $settings): View
    {
        $sections = JakZacitSection::orderBy('sort_order')->get();

        return view('jak-zacit', [
            'settings' => $settings,
            'sections' => $sections,
            'jumpNavItems' => [
                ...$sections->map(fn (JakZacitSection $section) => [
                    'text' => $section->nav_label,
                    'url' => '#'.$section->anchor,
                ])->all(),
                ['text' => 'Chci se zlepšit', 'url' => '#doporuceni'],
            ],
        ]);
    }
}
