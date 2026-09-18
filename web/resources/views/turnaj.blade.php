{{--
    Mirrors ui/src/turnaj.njk + pravidelny-turnaj.njk — those are two hardcoded example pages in
    ui/ (one-off "Svazový" tournament vs. recurring "Pravidelný" one), both built from the same
    tournamentContent() widget; here it's one real per-record detail page instead (see
    TournamentController::show()). $tournament: App\Models\Tournament, eager-loaded category.
--}}
@php
    $structuredData = $tournament->start_date ? [
        '@context' => 'https://schema.org',
        '@type' => 'SportsEvent',
        'name' => $tournament->title,
        'startDate' => $tournament->start_date->toDateString(),
        'endDate' => $tournament->end_date?->toDateString(),
        'location' => [
            '@type' => 'Place',
            'name' => $tournament->location_text ?: __('Česká republika'),
        ],
        'organizer' => [
            '@type' => 'Organization',
            'name' => __('Český svaz poolbilliardu'),
        ],
        'url' => $tournament->url ?: url()->current(),
    ] : null;
@endphp
<x-layouts.app
    :title="$tournament->seo_title ?: $tournament->title"
    :description="$tournament->seo_description ?: ($tournament->description ? \Illuminate\Support\Str::of($tournament->description)->stripTags()->squish()->limit(155)->toString() : $tournament->title.' — '.__('turnaj v kalendáři Českého poolbilliardu.'))"
    og-type="article"
    :og-image="$tournament->seo_image_url"
    :structured-data="$structuredData"
>
    <x-tournament-content
        :back-url="\App\Support\Locale::route('kalendar')"
        :tag-text="$tournament->category?->name"
        :tag-color="$tournament->category?->color"
        :title="$tournament->title"
        :date-text="$tournament->date_text"
        :location-text="$tournament->location_text"
        class="bg-gradient-light"
    >
        @if ($tournament->description)
            <div class="c-tournament-detail__card">
                {!! $tournament->description !!}
            </div>
        @endif

        @if ($tournament->url)
            <x-button
                :text="__('Přihlášky a detail turnaje')"
                :url="$tournament->url"
                color="primary"
                icon="arrow-top-right-on-square"
                target="_blank"
            />
        @endif
    </x-tournament-content>

    <x-newsletter />
</x-layouts.app>
