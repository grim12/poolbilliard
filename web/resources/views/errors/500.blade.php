{{--
    Laravel's conventional error view path. Deliberately does NOT use <x-layouts.app> — a 500 can
    happen precisely because the DB is unreachable, and that layout reads GeneralSettings from
    the DB for the header toggle; reusing it here risks a second failure while rendering the
    error page for the first one. Kept static: no settings, no header/footer partials.
--}}
<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Chyba serveru — Poolbilliard</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-body-main antialiased font-sans flex items-center justify-center p-4">
    <div class="max-w-sm text-center">
        <h1 class="mb-3">Chyba serveru (500)</h1>
        <p class="p--lg mb-6">Něco se pokazilo na naší straně. Zkuste to prosím za chvíli znovu.</p>
        <a href="{{ url('/') }}" class="c-button c-button--with-arrow">Zpět na hlavní stránku</a>
    </div>
</body>
</html>
