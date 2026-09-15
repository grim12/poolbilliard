<?php

namespace App\Http\Controllers;

use App\Models\Myth;
use App\Models\RuleCard;
use App\Settings\PravidlaSettings;
use Illuminate\View\View;

class PravidlaController extends Controller
{
    /**
     * Mirrors ui/src/pravidla.njk. $settings holds just the 3 section headers; $myths and
     * $ruleCards are separate models (repeating records with their own admin CRUD).
     */
    public function index(PravidlaSettings $settings): View
    {
        return view('pravidla', [
            'settings' => $settings,
            'myths' => Myth::orderBy('sort_order')->get(),
            'ruleCards' => RuleCard::orderBy('sort_order')->get(),
        ]);
    }
}
