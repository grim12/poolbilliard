{{--
    Mirrors ui/src/turnaj.njk + pravidelny-turnaj.njk — those are two hardcoded example pages in
    ui/ (one-off "Svazový" tournament vs. recurring "Pravidelný" one), both built from the same
    tournamentContent() widget; here it's one real per-record detail page instead (see
    TournamentController::show()). $tournament: App\Models\Tournament, eager-loaded category.
--}}
<x-layouts.app :title="$tournament->title.' — Poolbilliard'">
    <x-tournament-content
        back-url="{{ route('kalendar') }}"
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
                text="Přihlášky a detail turnaje"
                :url="$tournament->url"
                color="primary"
                icon="arrow-top-right-on-square"
                target="_blank"
            />
        @endif
    </x-tournament-content>

    <x-newsletter />
</x-layouts.app>
