<?php

namespace App\Http\Controllers;

use App\Enums\HernaStatus;
use App\Enums\Region;
use App\Enums\Sport;
use App\Http\Requests\StoreHernaRegistrationRequest;
use App\Models\Herna;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegistraceHernyController extends Controller
{
    /**
     * Same 7 days as Hernas/Schemas/HernaForm.php's own DAYS constant (that one's private, and
     * this form's day inputs are fixed rows rather than a reorderable Repeater, so duplicating
     * the short literal list isn't worth extracting into something shared).
     */
    private const DAYS = ['Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota', 'Neděle'];

    /**
     * "Registrace herny" — mirrors ui/src/registrace-herny.njk. Public submission form for a
     * new Herna. No admin auth, no Filament involved on this side.
     */
    public function create(): View
    {
        return view('registrace-herny', [
            'regions' => Region::cases(),
            'sports' => Sport::cases(),
            'days' => self::DAYS,
        ]);
    }

    /**
     * Creates the Herna directly with HernaStatus::Pending — same table/model real approved
     * herny use (not a separate staging table), so approving a submission in
     * Hernas/Tables/HernasTable.php's "Schválit" action is just flipping one column, no data
     * migration step. HernaController::index()/show() already gate on
     * `status === HernaStatus::Approved`, so a pending record never leaks publicly in the
     * meantime.
     */
    public function store(StoreHernaRegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Herna::create([
            'name' => $validated['name'],
            'about_text' => $validated['description'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'region' => $validated['region'],
            'lat' => $validated['lat'] ?? 0,
            'lng' => $validated['lng'] ?? 0,
            'sports' => $validated['sports'],
            'hours' => collect($validated['hours'] ?? [])
                ->filter()
                ->map(fn (string $text, string $day) => ['day' => $day, 'text' => $text])
                ->values()
                ->all(),
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'website' => $validated['website'] ?? null,
            'status' => HernaStatus::Pending,
        ]);

        // TODO: e-mail the submitted data to the section admin (destination address TBD).
        // TODO: e-mail a confirmation copy to the submitter (the "Kontakt" e-mail field above,
        // when filled in — it's currently the herna's own public contact address, not
        // necessarily distinct from the submitter, but there's no separate "your e-mail" field
        // in ui/'s mock to send it to instead).

        return redirect()->route('registrace-herny')->with('submitted', true);
    }
}
