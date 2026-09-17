{{--
    <x-hp-featured-articles :featured :items :more-url :more-text />
    - featured: single Article model (the big hero card)
    - items: collection of Article models (compact sidebar list, e.g. the next 4 most recent)
    Mirrors ui/src/_includes/widgets/hp-featured-articles.njk. No visible title (sr-only
    "Novinky" heading only), same as ui/'s own widget.
--}}
@props([
    'featured',
    'items' => [],
    'moreUrl' => '#',
    'moreText' => null,
])

@php
    $moreText ??= __('Další novinky');
@endphp

<section {{ $attributes->merge(['class' => 'c-section c-section--hp-featured-articles']) }}>
    <div class="c-container">
        <h2 class="sr-only">{{ __('Novinky') }}</h2>
        <div class="c-section__grid">
            <div class="c-section__featured">
                <x-article-card-main
                    :title="$featured->title"
                    :url="route('novinky.show', $featured->slug_cs)"
                    :image="$featured->image_url"
                    :tag-text="$featured->category?->name"
                    :tag-color="$featured->category?->color"
                    :date="$featured->date_text"
                />
            </div>
            <div class="c-section__sidebar">
                <div class="c-section__sidebar-list">
                    @foreach ($items as $item)
                        <x-article-card-compact
                            :title="$item->title"
                            :url="route('novinky.show', $item->slug_cs)"
                            :image="$item->image_url"
                            :tag-text="$item->category?->name"
                            :tag-color="$item->category?->color"
                            :date="$item->date_text"
                        />
                    @endforeach
                </div>
                <x-button :text="$moreText" :url="$moreUrl" variant="outline" class="w-full" />
            </div>
        </div>
    </div>
</section>
