{{--
    <x-notice-card :title :url :tag-text :tag-color :tag-variant :date :excerpt :size
                   :heading-level />
    - size: "md" (default) — compact row with trailing arrow | "lg" — bigger stacked card with
      excerpt, no arrow. Mirrors ui/src/_includes/macros/card/notice.njk.
--}}
@props([
    'title',
    'url' => '#',
    'tagText' => '',
    'tagColor' => 'accent',
    'tagVariant' => 'subtle',
    'date' => '',
    'excerpt' => '',
    'size' => 'md',
    'headingLevel' => 'h3',
])

@php
    $classes = collect(['c-card', 'c-card--notice', $size === 'lg' ? 'c-card--lg' : null])->filter()->implode(' ');
@endphp

<article {{ $attributes->merge(['class' => $classes]) }}>
    <a href="{{ $url }}" class="c-card__link" aria-label="{{ $title }}">
        <div class="c-card__content">
            @if ($tagText || $date)
                <div class="c-card__meta">
                    @if ($tagText)
                        <x-tag :text="$tagText" :color="$tagColor" :variant="$tagVariant" size="sm" />
                    @endif
                    @if ($date)
                        <time class="c-card__date">{{ $date }}</time>
                    @endif
                </div>
            @endif
            <{{ $headingLevel }} class="c-card__title">{{ $title }}</{{ $headingLevel }}>
            @if ($size === 'lg' && $excerpt)
                <p class="c-card__excerpt">{{ $excerpt }}</p>
            @endif
        </div>
        @if ($size !== 'lg')
            <x-heroicon-m-chevron-right class="c-card__arrow" width="24" height="24" />
        @endif
    </a>
</article>
