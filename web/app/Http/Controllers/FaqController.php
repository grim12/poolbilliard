<?php

namespace App\Http\Controllers;

use App\Models\FaqItem;
use App\Settings\FaqSettings;
use Illuminate\View\View;

class FaqController extends Controller
{
    /**
     * "Časté dotazy" — mirrors ui/src/faq.njk (accordion only, no site chrome yet).
     */
    public function index(FaqSettings $settings): View
    {
        return view('faq', [
            'items' => FaqItem::whereHas('groups', fn ($query) => $query->where('slug', 'obecne'))
                ->orderBy('sort_order')
                ->get(),
            'settings' => $settings,
        ]);
    }
}
