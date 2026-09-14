{{--
    <x-layouts.app :title="..." :has-gallery="true" :has-map="true">
        ...page content...
    </x-layouts.app>
    Mirrors ui/src/_includes/layouts/base.njk (the <html>/<head>/<body> shell) plus the
    <div class="c-page-wrapper"> + header/footer wiring every ui/ page repeats inline.
    hasGallery/hasMap are accepted for parity with ui/'s front-matter flags but don't gate
    anything yet — GLightbox/Leaflet aren't ported (no vendor asset pipeline for them here yet).
--}}
@props([
    'title' => 'Poolbilliard',
    'headerDark' => false,
    'hasGallery' => false,
    'hasMap' => false,
])

<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet" />
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-body-main antialiased font-sans">
    <div class="c-page-wrapper">
        @include('layouts.header', ['headerDark' => $headerDark])

        <main id="main-content">
            {{ $slot }}
        </main>

        @include('layouts.footer')
    </div>
</body>
</html>
