{{--
    <x-gallery :items :title />
    - items: array of ['image' => string, 'alt' => string] — full-size photos.
    Mirrors ui/src/_includes/widgets/gallery.njk. NOTE: GLightbox isn't ported yet (no vendor
    asset pipeline for it here), so tiles just open the full image in a new tab instead of a
    lightbox — swap target="_blank" back out once GLightbox is wired up.
--}}
@props([
    'items' => [],
    'title' => '',
])

@if (count($items))
    <section {{ $attributes->merge(['class' => 'c-section c-section--gallery']) }}>
        <div class="c-container">
            @if ($title)
                <div class="c-section__header">
                    <h4 class="c-section__title h4">{{ $title }}</h4>
                </div>
            @endif
            <div class="c-section__grid">
                @foreach ($items as $item)
                    <a href="{{ $item['image'] }}" target="_blank" rel="noopener noreferrer" class="c-section__item" aria-label="{{ $item['alt'] ?: $title }}">
                        <img src="{{ $item['image'] }}" alt="" loading="lazy" />
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
