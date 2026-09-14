<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\View\View;

class NoticeController extends Controller
{
    /**
     * "Zprávy výboru" — mirrors ui/src/zpravodajstvi/vykonny-vybor.njk (card grid only; the
     * "Kontakt" info box ui/ places in newsGrid's sidebar is skipped for now, same scope cut
     * as Novinky's "Důležité zprávy" sidebar — no sidebar layout exists yet).
     */
    public function index(): View
    {
        $notices = Notice::whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->paginate(6);

        return view('zpravodajstvi.vykonny-vybor', [
            'notices' => $notices,
        ]);
    }

    /**
     * Single notice — mirrors ui/src/zpravodajstvi/vykonny-vybor-detail.njk, which reuses the
     * same articleContent() widget as a regular article (just tagPosition="inline", accent
     * color) — no separate "notice content" component needed on the Blade side either.
     */
    public function show(Notice $notice): View
    {
        return view('zpravodajstvi.vykonny-vybor-detail', [
            'notice' => $notice,
        ]);
    }
}
