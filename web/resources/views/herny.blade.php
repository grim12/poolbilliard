{{--
    Mirrors ui/src/herny.njk — map + info panel hero, then the flat herna card grid.
    $hernas: only HernaStatus::Approved records (see HernaController::index()).
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

<x-layouts.app title="Kulečníkové herny — Poolbilliard">
    <div class="bg-gradient-light">
        <x-news-header
            title="Kulečníkové herny"
            subtitle="Najděte si hernu ve svém regionu! Objevte místa, kde si můžete zahrát poolbilliard, potrénovat nebo poznat další hráče."
            :show-search="false"
            class="pb-none"
        />

        <section class="c-section pt-none">
            <div class="c-container">
                <div class="c-section__grid lg:grid-cols-[1.7fr_1fr] items-stretch gap-6 lg:gap-8">
                    <div class="c-club-map" data-club-map='{{ json_encode($mapHernas) }}' data-pin-color="primary" role="application" aria-label="Mapa heren v České republice"></div>

                    <x-info-panel
                        tag-text="Přidejte svou hernu"
                        title="Provozujete hernu?"
                        text="Zařaďte ji do našeho katalogu. Vyplňte jednoduchý registrační formulář a po schválení se herna objeví v seznamu i na mapě, kde si ji najdou hráči z vašeho okolí."
                        button-text="Registrovat hernu"
                        button-url="/registrace-herny"
                    />
                </div>
            </div>
        </section>
    </div>

    <x-herna-list :items="$hernas" :regions="$regions" class="pt-none" />

    <x-newsletter />
</x-layouts.app>
