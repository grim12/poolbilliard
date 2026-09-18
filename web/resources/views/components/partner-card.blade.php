{{--
    <x-partner-card :name :image :url />
    - Bordered logo card + caption, whole card links out to the partner's site — for the full
      partner directory grid (Partneři). Mirrors ui/src/_includes/macros/partner-card.njk.
    - url: falls back to "#" if not supplied yet.
--}}
@props([
    'name',
    'image' => null,
    'url' => '#',
])

<a class="c-partner-card" href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $name }}">
    <div class="c-partner-card__logo">
        <img src="{{ $image }}" alt="" loading="lazy" />
    </div>
    <p class="c-partner-card__name">{{ $name }}</p>
</a>
