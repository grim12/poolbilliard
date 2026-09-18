{{--
    Mirrors ui/src/pravidelny-turnaj.njk. $tournament: App\Models\RecurringTournament,
    eager-loaded herna. Reuses <x-tournament-content>, same as the "svazový" Tournament detail
    page (resources/views/turnaj.blade.php) — the tag/color classification ("Amatérský turnaj",
    gold) is fixed, since every RecurringTournament is inherently this kind (unlike Tournament,
    which has its own admin-managed category).
--}}
<x-layouts.app
    :title="$tournament->seo_title ?: $tournament->title"
    :description="$tournament->seo_description ?: ($tournament->description ? \Illuminate\Support\Str::of($tournament->description)->stripTags()->squish()->limit(155)->toString() : $tournament->title.' — '.__('pravidelný turnaj v kalendáři Českého poolbilliardu.'))"
    og-type="article"
    :og-image="$tournament->seo_image_url"
>
    <x-tournament-content
        :back-url="\App\Support\Locale::route('kalendar')"
        :tag-text="__('Amatérský turnaj')"
        tag-color="gold"
        :title="$tournament->title"
        :date-text="$tournament->frequency"
        :location-text="$tournament->location_text"
        class="bg-gradient-light"
    >
        @if ($tournament->description)
            <div class="c-tournament-detail__card">
                {!! $tournament->description !!}
            </div>
        @endif

        @if ($tournament->herna)
            <div class="c-tournament-detail__card">
                <h2 class="c-tournament-detail__card-title">{{ __('Odkazy') }}</h2>
                <a href="{{ route('herna.show', $tournament->herna) }}" class="c-tournament-detail__link">
                    <x-heroicon-m-map-pin width="18" height="18" />
                    {{ __('Detail herny — :name', ['name' => $tournament->herna->name]) }}
                </a>
            </div>
        @endif

        @if ($tournament->url)
            <x-button
                :text="__('Kontaktovat organizátora')"
                :url="$tournament->url"
                color="primary"
                icon="chevron-right"
            />
        @endif
    </x-tournament-content>

    <x-newsletter />
</x-layouts.app>
