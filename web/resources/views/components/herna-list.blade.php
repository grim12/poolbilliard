{{--
    <x-herna-list :items :regions :search-placeholder />
    - Card grid of herny (pool halls) with a search + kraj filter bar above it. Distinct from
      club-directory: herny are public venues, not member-only clubs — no detail page link
      variation and no region grouping (flat grid).
    - Search input and Kraj select are intentionally inert (no real filtering wired up), same as
      ui/ — see components/news-header.blade.php's own doc comment for the same pattern.
    Mirrors ui/src/_includes/widgets/herna-list.njk.
--}}
@use('Illuminate\Support\Str')

@props([
    'items' => [],
    'regions' => [],
    'searchPlaceholder' => 'Hledat podle názvu, města, adresy…',
])

<section {{ $attributes->merge(['class' => 'c-section c-section--herna-list']) }}>
    <div class="c-container">
        <div class="c-herna-filter">
            <div class="c-herna-filter__field c-herna-filter__field--search">
                <label class="c-herna-filter__label" for="herna-search">Vyhledat hernu</label>
                <div class="c-herna-filter__input-wrap">
                    <x-heroicon-m-magnifying-glass width="18" height="18" class="c-herna-filter__icon" />
                    <input type="search" id="herna-search" class="c-herna-filter__input" placeholder="{{ $searchPlaceholder }}" data-herna-search />
                </div>
            </div>
            @if (count($regions))
                <div class="c-herna-filter__field c-herna-filter__field--region">
                    <label class="c-herna-filter__label" for="herna-region">Kraj</label>
                    <select id="herna-region" class="c-herna-filter__select" data-herna-region>
                        <option value="">Vše</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->value }}">{{ $region->getLabel() }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="c-herna-grid">
            @foreach ($items as $herna)
                <div class="c-herna-card">
                    <div class="c-herna-card__header">
                        <span class="c-herna-card__badge" aria-hidden="true">{{ Str::initials($herna->name) }}</span>
                        <div class="min-w-0">
                            <a class="c-herna-card__name" href="{{ route('herna.show', $herna) }}">{{ $herna->name }}</a>
                            <p class="c-herna-card__address">
                                <x-heroicon-m-map-pin width="14" height="14" />
                                {{ collect([$herna->address, $herna->city])->filter()->implode(', ') }}
                            </p>
                        </div>
                    </div>
                    @if ($herna->website)
                        <div class="c-herna-card__divider"></div>
                        <a class="c-herna-card__link" href="{{ $herna->website }}" target="_blank" rel="noopener noreferrer">
                            <x-heroicon-m-globe-alt width="14" height="14" />
                            Web
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
