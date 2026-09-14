{{--
    <x-news-header :title :subtitle :back-text :back-url :show-search :search-placeholder
                   :categories />
    - categories: array of ['text' => string, 'value' => string, 'isActive' => bool] — simple
    single-color filter pills, hidden when empty.
    - Optional `aside` slot: custom content right-aligned next to the title/subtitle on desktop
      (mirrors ui/'s {% call newsHeader(...) %} block).
    - Search input and category filter are intentionally inert (no real filtering wired up
      yet), same as ui/ — see ui/src/_includes/widgets/news-header.njk's own doc comment.
    Mirrors ui/src/_includes/widgets/news-header.njk.
--}}
@props([
    'title' => 'Novinky',
    'subtitle' => '',
    'backText' => 'Zpět na novinky',
    'backUrl' => '',
    'showSearch' => true,
    'searchPlaceholder' => 'Hledej ve zprávách',
    'categories' => [],
])

<section {{ $attributes->merge(['class' => 'c-section c-section--news-header']) }}>
    <div class="c-container">
        @if ($backUrl)
            <x-back-link :text="$backText" :url="$backUrl" class="c-news-header__back" />
        @endif

        <div class="c-news-header__top">
            <div class="c-news-header__intro">
                <h1 @class(['h1--big', 'c-news-header__title', 'c-news-header__title--has-subtitle' => $subtitle])>{{ $title }}</h1>
                @if ($subtitle)
                    <p class="c-news-header__subtitle p--lg">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($aside)
                <div class="c-news-header__aside">
                    {{ $aside }}
                </div>
            @endisset
        </div>

        @if ($showSearch)
            <div class="c-news-header__search">
                <x-heroicon-m-magnifying-glass class="c-news-header__search-icon" width="18" height="18" />
                <input type="search" class="c-news-header__search-input" placeholder="{{ $searchPlaceholder }}" aria-label="{{ $searchPlaceholder }}" data-news-search />
            </div>
        @endif

        @if (count($categories))
            <div class="c-news-header__filters" role="group" aria-label="Filtrovat podle kategorie">
                @foreach ($categories as $cat)
                    <button type="button" class="c-news-header__filter" aria-pressed="{{ ($cat['isActive'] ?? false) ? 'true' : 'false' }}" data-category="{{ $cat['value'] }}">{{ $cat['text'] }}</button>
                @endforeach
            </div>
        @endif
    </div>
</section>
