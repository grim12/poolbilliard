{{--
    <x-related-articles :title :items />
    - items: collection of Article models. Mirrors ui/src/_includes/widgets/related-articles.njk
    — category->name/color map onto article-card's generic tagText/tagColor props.
--}}
@props([
    'title' => null,
    'items' => [],
])

@php
    $title ??= __('Další články');
@endphp

<section {{ $attributes->merge(['class' => 'c-section c-section--related-articles']) }}>
    <div class="c-container">
        <div class="c-section__header">
            <h2 class="c-section__title">{{ $title }}</h2>
        </div>
        <div class="c-section__grid">
            @foreach ($items as $item)
                <x-article-card
                    :title="$item->title"
                    :url="route('novinky.show', $item->slug_cs)"
                    :image="$item->image_url"
                    :tag-text="$item->category?->name"
                    :tag-color="$item->category?->color"
                    :date="$item->date_text"
                    :excerpt="$item->excerpt"
                />
            @endforeach
        </div>
    </div>
</section>
