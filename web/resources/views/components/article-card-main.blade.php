{{--
    <x-article-card-main :title :url :image :tag-text :tag-color :tag-variant :date :heading-level />
    - Large hero-style card (dark overlay, big title) for the single featured article on the
    homepage. Mirrors ui/src/_includes/macros/card/article-main.njk (card_article_main).
--}}
@props([
    'title',
    'url' => '#',
    'image' => '',
    'imageAlt' => '',
    'tagText' => '',
    'tagColor' => 'accent',
    'tagVariant' => 'solid',
    'date' => '',
    'headingLevel' => 'h3',
])

<article {{ $attributes->merge(['class' => 'c-card c-card--article-main t-dark']) }}>
    <a href="{{ $url }}" class="c-card__link" aria-label="{{ $title }}">
        @if ($image)
            <div class="c-card__media">
                <img src="{{ $image }}" alt="{{ $imageAlt }}" loading="lazy" />
            </div>
        @endif
        <div class="c-card__overlay"></div>
        <div class="c-card__body">
            @if ($tagText || $date)
                <div class="c-card__meta">
                    @if ($tagText)
                        <x-tag :text="$tagText" :color="$tagColor" :variant="$tagVariant" />
                    @endif
                    @if ($date)
                        <time class="c-card__date">{{ $date }}</time>
                    @endif
                </div>
            @endif
            <{{ $headingLevel }} class="h1--big c-card__title">{{ $title }}</{{ $headingLevel }}>
        </div>
    </a>
</article>
