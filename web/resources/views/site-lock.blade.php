{{--
    Rendered by SiteLock middleware for anyone unauthenticated when the lock is active. Not a
    designed page (no ui/ counterpart) — it's a temporary ops wall, not part of the public site.
--}}
<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet" />
    <title>Přístup — Poolbilliard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-body-main antialiased font-sans flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <h1 class="mb-3 text-center">Web se připravuje</h1>
        <p class="p--sm mb-6 text-center">Přihlaste se stejnými údaji jako do administrace.</p>

        <form class="c-form" method="POST" action="{{ route('site-lock.attempt') }}">
            @csrf

            <div class="c-form__group">
                <div class="c-form__grid">
                    <label class="c-form__field c-form__field--full">
                        <span class="c-form__label">E-mail</span>
                        <input type="email" name="email" class="c-input" value="{{ old('email') }}" required autofocus />
                        @error('email') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="c-form__field c-form__field--full">
                        <span class="c-form__label">Heslo</span>
                        <input type="password" name="password" class="c-input" required />
                    </label>
                </div>
            </div>

            <x-button text="Přihlásit" type="submit" :has-arrow="false" class="w-full justify-center mt-2" />
        </form>
    </div>
</body>
</html>
