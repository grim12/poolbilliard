{{--
    <x-layouts.app :title="..." :description="..." :has-gallery="true" :has-map="true">
        ...page content...
    </x-layouts.app>
    Mirrors ui/src/_includes/layouts/base.njk (the <html>/<head>/<body> shell) plus the
    <div class="c-page-wrapper"> + header/footer wiring every ui/ page repeats inline.
    hasGallery/hasMap are accepted for parity with ui/'s front-matter flags but don't gate
    anything yet — GLightbox/Leaflet aren't ported (no vendor asset pipeline for them here yet).
    headerDark: omit it (the default) to use the sitewide GeneralSettings::$header_dark toggle
    (admin-editable, light by default) — pass true/false explicitly only if one specific page
    needs to override that global choice, which no page does today.
    description: plain-text meta/OG/Twitter description — every page should pass one.
    ogImage/ogType/canonical: optional overrides; ogImage falls back to the site logo (no
    dedicated 1200x630 social image exists yet — see README's SEO section), canonical falls
    back to the current URL without its query string.
    structuredData: optional JSON-LD array (schema.org) specific to the page — e.g. a
    NewsArticle/Event — rendered alongside the sitewide SportsOrganization block every page
    already gets.
--}}
@props([
    'title' => 'Poolbilliard',
    'description' => null,
    'ogImage' => null,
    'ogType' => 'website',
    'canonical' => null,
    'structuredData' => null,
    'headerDark' => null,
    'hasGallery' => false,
    'hasMap' => false,
])

@php
    $headerDark ??= app(\App\Settings\GeneralSettings::class)->header_dark;
    $canonicalUrl = $canonical ?? url()->current();
    $ogImageUrl = $ogImage ?? asset('uploads/cesky_pool.png');
@endphp

<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @if (\App\Support\Launch::indexable())
        <meta name="robots" content="index, follow" />
    @else
        <meta name="robots" content="noindex, nofollow, noarchive, nosnippet" />
    @endif
    <title>{{ $title }}</title>
    @if ($description)
        <meta name="description" content="{{ $description }}" />
    @endif
    <link rel="canonical" href="{{ $canonicalUrl }}" />

    <meta property="og:type" content="{{ $ogType }}" />
    <meta property="og:site_name" content="Český Poolbilliard" />
    <meta property="og:locale" content="cs_CZ" />
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:url" content="{{ $canonicalUrl }}" />
    <meta property="og:image" content="{{ $ogImageUrl }}" />
    @if ($description)
        <meta property="og:description" content="{{ $description }}" />
    @endif

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $title }}" />
    <meta name="twitter:image" content="{{ $ogImageUrl }}" />
    @if ($description)
        <meta name="twitter:description" content="{{ $description }}" />
    @endif

    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'SportsOrganization',
        'name' => 'Český svaz poolbilliardu',
        'url' => url('/'),
        'logo' => asset('uploads/cesky_pool.png'),
        'sameAs' => [
            'https://www.facebook.com/ceskypool',
            'https://www.instagram.com/ceskypool/',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @if ($structuredData)
        <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

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
