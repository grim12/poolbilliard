{{--
    <x-leaderboards :title :subtitle :items :more-url :more-text :max-entries :id />
    - items: collection of Leaderboard models (title, entries, featured). Mirrors
      ui/src/_includes/widgets/leaderboards.njk. Each card's "Detail série"/"Celý žebříček" link
      has no real destination yet (no per-series page exists) — same as ui/'s own "#" placeholder.
    - maxEntries: passed straight through to each <x-leaderboard> — see its own doc comment.
--}}
@props([
    'title' => '',
    'subtitle' => '',
    'items' => [],
    'moreUrl' => '#',
    'moreText' => null,
    'maxEntries' => null,
    'id' => '',
])

@php
    $moreText ??= __('Systémy soutěží');
@endphp

<section {{ $attributes->merge(['class' => 'c-section c-section--leaderboards']) }} @if ($id) id="{{ $id }}" @endif>
    <div class="c-container">
        <div class="c-section__header">
            <h2 class="c-section__title">{{ $title }}</h2>
            @if ($subtitle)
                <p class="c-section__subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="c-section__grid">
            @foreach ($items as $item)
                <x-leaderboard
                    :title="$item->title"
                    :entries="$item->entries"
                    :featured="$item->featured"
                    :link-text="$item->featured ? __('Celý žebříček') : __('Detail série')"
                    :max-entries="$maxEntries"
                />
            @endforeach
        </div>
        <div class="c-section__footer">
            <x-button :text="$moreText" :url="$moreUrl" variant="outline" />
        </div>
    </div>
</section>
