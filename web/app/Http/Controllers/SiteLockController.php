<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * The unlock screen SiteLock middleware redirects to. Authenticates against the same `users`
 * table as the Filament admin (App\Models\User) — no separate credential store to manage.
 */
class SiteLockController extends Controller
{
    public function show(): View
    {
        return view('site-lock');
    }

    public function attempt(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Nesprávný e-mail nebo heslo.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended('/');
    }
}
