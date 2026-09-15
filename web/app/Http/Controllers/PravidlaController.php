<?php

namespace App\Http\Controllers;

use App\Models\RuleCard;
use App\Settings\PravidlaSettings;
use Illuminate\View\View;

class PravidlaController extends Controller
{
    /**
     * Mirrors ui/src/pravidla.njk. $settings holds the 3 section headers + the myth/fact
     * repeater; $ruleCards is a separate model (repeating, image-bearing records).
     */
    public function index(PravidlaSettings $settings): View
    {
        return view('pravidla', [
            'settings' => $settings,
            'ruleCards' => RuleCard::orderBy('sort_order')->get(),
        ]);
    }
}
