{{--
    <x-club-directory :title :subtitle :clubs :add-button-text :add-button-url />
    - clubs: Collection of Club models, already grouped by region (see ClubController::index())
      — each group rendered as its own table. Mirrors ui/src/_includes/widgets/club-directory.njk
      (there groupby() runs inside the macro over a flat array; here the grouping happens in the
      controller since it's a real Eloquent collection, not a plain mock array).
--}}
@props([
    'title' => null,
    'subtitle' => null,
    'clubs' => [],
    'addButtonText' => null,
    'addButtonUrl' => '#',
])

@php
    $title ??= __('Seznam klubů');
    $subtitle ??= __('Vyber si klub ve svém okolí a udělej první krok do světa závodního poolbilliardu.');
    $addButtonText ??= __('Zaregistrovat nový klub');
@endphp

<section {{ $attributes->merge(['class' => 'c-section c-section--club-directory']) }}>
    <div class="c-container">
        <div class="c-club-directory__header">
            <div class="c-club-directory__intro">
                <h2 class="c-section__title">{{ $title }}</h2>
                @if ($subtitle)
                    <p class="c-section__subtitle">{{ $subtitle }}</p>
                @endif
            </div>
            <x-button :text="$addButtonText" :url="$addButtonUrl" color="primary" leading-icon="plus" :has-arrow="false" class="shrink-0" />
        </div>

        <div class="c-club-directory__groups">
            @foreach ($clubs as $region => $items)
                @php
                    $regionLabel = $region ? \App\Enums\Region::from($region)->getLabel() : $region;
                @endphp
                <div class="c-club-group">
                    <h3 class="c-club-group__title">{{ $regionLabel }}</h3>
                    <div class="c-club-group__table" role="table" aria-label="{{ __('Kluby v regionu :region', ['region' => $regionLabel]) }}">
                        <div class="c-club-group__row c-club-group__row--head" role="row">
                            <span class="c-club-group__cell c-club-group__cell--head" role="columnheader">{{ __('Název klubu') }}</span>
                            <span class="c-club-group__cell c-club-group__cell--head" role="columnheader">{{ __('Město') }}</span>
                            <span class="c-club-group__cell c-club-group__cell--head" role="columnheader" aria-hidden="true"></span>
                        </div>
                        @foreach ($items as $club)
                            <a href="{{ route('klub.show', $club) }}" class="c-club-group__row c-club-group__row--link" role="row" aria-label="{{ $club->name }}">
                                <span class="c-club-group__cell c-club-group__cell--name" role="cell">{{ $club->name }}</span>
                                <span class="c-club-group__cell c-club-group__cell--city" role="cell">
                                    <x-heroicon-m-map-pin width="16" height="16" />
                                    {{ $club->city }}
                                </span>
                                <span class="c-club-group__cell c-club-group__cell--action" role="cell">
                                    {{ __('Detail') }}
                                    <x-heroicon-m-chevron-right width="16" height="16" />
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
