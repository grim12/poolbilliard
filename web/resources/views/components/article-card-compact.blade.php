{{--
    <x-article-card-compact :title :url :image :tag-text :tag-color :tag-variant :date :heading-level />
    - Small thumbnail row for a sidebar list. Mirrors
    ui/src/_includes/macros/card/article-compact.njk (card_article_compact).
--}}
@props([
    'title',
    'url' => '#',
    'image' => '',
    'imageAlt' => '',
    'tagText' => '',
    'tagColor' => 'primary',
    'tagVariant' => 'plain',
    'date' => '',
    'headingLevel' => 'h3',
])

<article {{ $attributes->merge(['class' => 'c-card c-card--article-compact']) }}>
    <a href="{{ $url }}" class="c-card__link" aria-label="{{ $title }}">
        @if ($image)
            <div class="c-card__media">
                <img src="{{ $image }}" alt="{{ $imageAlt }}" loading="lazy" />
            </div>
        @endif
        <div class="c-card__body">
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
        </div>
    </a>
</article>
