<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\View\View;

class PartnerController extends Controller
{
    /**
     * Full partner directory ("Seznam partnerů") — mirrors ui/src/partneri.njk.
     */
    public function index(): View
    {
        return view('partneri', [
            'partners' => Partner::orderBy('sort_order')->get(),
        ]);
    }
}
