{{--
    <x-article-card :title :url :image :image-alt :tag-text :tag-color :tag-variant :date
                    :excerpt :heading-level />
    - Vertical card for a listing grid: image on top, tag + date, title, short excerpt below.
    Mirrors ui/src/_includes/macros/card/article-grid.njk (card_article_grid).
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
    'excerpt' => '',
    'headingLevel' => 'h3',
])

<article {{ $attributes->merge(['class' => 'c-card c-card--article-grid']) }}>
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
            @if ($excerpt)
                <p class="c-card__excerpt">{{ $excerpt }}</p>
            @endif
        </div>
    </a>
</article>
