{{--
    <x-hp-partners :title :items :more-url :more-text />
    - items: collection of Partner models — plain logo wall (no link/name caption), different
    from the full directory's <x-partner-card>. Mirrors ui/src/_includes/widgets/partners.njk.
--}}
@props([
    'title',
    'items' => [],
    'moreUrl' => '#',
    'moreText' => 'Partneři a sponzoři',
])

<section {{ $attributes->merge(['class' => 'c-section c-section--partners']) }}>
    <div class="c-container">
        <div class="c-section__header">
            <h2 class="c-section__title">{{ $title }}</h2>
        </div>
        <div class="c-partners">
            @foreach ($items as $item)
                <span class="c-partners__item">
                    <img class="c-partners__logo" src="{{ $item->logo_url }}" alt="{{ $item->name }}" loading="lazy" />
                </span>
            @endforeach
        </div>
        <div class="c-section__footer">
            <x-button :text="$moreText" :url="$moreUrl" variant="outline" />
        </div>
    </div>
</section>
