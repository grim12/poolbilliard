{{--
    Mirrors ui/src/herny.njk — map + info panel hero, then the flat herna card grid.
    $hernas: only HernaStatus::Approved records (see HernaController::index()).
    $settings: App\Settings\KlubyHernySettings — editorial text for the heading/info panel.
--}}
@php
    $mapHernas = $hernas->map(fn ($herna) => [
        'name' => $herna->name,
        'fullName' => $herna->name,
        'address' => $herna->address,
        'lat' => $herna->lat ? (float) $herna->lat : null,
        'lng' => $herna->lng ? (float) $herna->lng : null,
        'url' => route('herna.show', $herna),
    ])->filter(fn ($herna) => $herna['lat'] && $herna['lng'])->values();
@endphp

<x-layouts.app :title="$settings->herny_title.' — Poolbilliard'">
    <div class="bg-gradient-light">
        <x-news-header
            :title="$settings->herny_title"
            :subtitle="$settings->herny_subtitle"
            :show-search="false"
            class="pb-none"
        />

        <section class="c-section pt-none">
            <div class="c-container">
                <div class="c-section__grid lg:grid-cols-[1.7fr_1fr] items-stretch gap-6 lg:gap-8">
                    <div class="c-club-map" data-club-map='{{ json_encode($mapHernas) }}' data-pin-color="primary" role="application" aria-label="Mapa heren v České republice"></div>

                    <x-info-panel
                        :tag-text="$settings->herny_info_tag_text"
                        :title="$settings->herny_info_title"
                        :text="$settings->herny_info_text"
                        :button-text="$settings->herny_info_button_text"
                        button-url="/registrace-herny"
                    />
                </div>
            </div>
        </section>
    </div>

    <x-herna-list :items="$hernas" :regions="$regions" class="pt-none" />

    <x-newsletter />
</x-layouts.app>
