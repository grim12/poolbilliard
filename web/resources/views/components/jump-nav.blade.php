{{--
    <x-jump-nav :items />
    - Sticky row of small pill anchor links that scroll down to sections further on the same
      page — items: array of ['text', 'url'], where url is a same-page hash like "#regiony".
      The target section needs a matching `id` (see <x-content-section>'s `id` prop).
    - Scroll-spy (highlighting the pill for whichever section is in view, and the sticky
      behavior itself) is handled by [data-jump-nav]/[data-jump-link] in resources/js/app.js —
      already ported 1:1 from ui/'s main.js, just inert until now (no matching markup existed).
    Mirrors ui/src/_includes/macros/jump-nav.njk.
--}}
@props([
    'items' => [],
])

@if (count($items))
    <nav {{ $attributes->merge(['class' => 'c-jump-nav']) }} aria-label="Rychlá navigace na sekce stránky" data-jump-nav>
        <div class="c-container c-jump-nav__inner">
            @foreach ($items as $item)
                <a href="{{ $item['url'] }}" class="c-jump-nav__pill" data-jump-link>{{ $item['text'] }}</a>
            @endforeach
        </div>
    </nav>
@endif
