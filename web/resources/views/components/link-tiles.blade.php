{{--
    <x-link-tiles :items />
    - items: collection of LinkTile models (title, url, image_url) — which tiles and in what
      order is decided by the calling page, not by this component. Mirrors
      ui/src/_includes/widgets/link-tiles.njk.
--}}
@props([
    'items' => [],
])

<section {{ $attributes->merge(['class' => 'c-section c-section--link-tiles']) }}>
    <div class="c-container">
        <div class="c-section__grid">
            @foreach ($items as $item)
                <x-link-tile
                    :title="$item->title"
                    :url="$item->resolved_url"
                    :image="$item->image_url"
                />
            @endforeach
        </div>
    </div>
</section>
