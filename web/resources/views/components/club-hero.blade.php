{{--
    <x-club-hero :back-text :back-url :title :city :region :image :image-alt />
    - Full-bleed photo header for a club/herna detail page: breadcrumb + name + location, over
      a background photo. Generic (shared by /klub/{slug} and /herna/{slug}) — image is
      optional (falls back to a solid dark background via .t-dark when omitted, e.g. every
      herna today, since Herna has no hero image column).
    Mirrors ui/src/_includes/widgets/club-hero.njk.
--}}
@props([
    'backText' => 'Zpět na kluby',
    'backUrl' => '/kluby',
    'title' => '',
    'city' => '',
    'region' => '',
    'image' => '',
    'imageAlt' => '',
])

<section {{ $attributes->merge(['class' => 'c-section c-section--club-hero t-dark']) }}>
    @if ($image)
        <div class="c-section__media">
            <img src="{{ $image }}" alt="{{ $imageAlt }}" loading="lazy" />
        </div>
    @endif
    <div class="c-section__overlay"></div>

    <div class="c-container c-section__back-row">
        <x-back-link :text="$backText" :url="$backUrl" />
    </div>

    <div class="c-container c-section__content">
        <h1 class="c-section__title">{{ $title }}</h1>
        @if ($city || $region)
            <p class="c-section__meta">
                <x-heroicon-m-map-pin width="18" height="18" />
                {{ collect([$city, $region])->filter()->implode(' · ') }}
            </p>
        @endif
    </div>
</section>
