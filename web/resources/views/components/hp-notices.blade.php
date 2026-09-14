{{--
    <x-hp-notices :title :subtitle :items :more-url :more-text />
    - items: collection of Notice models (compact size="md" rows). Mirrors
    ui/src/_includes/widgets/notices.njk. Named hp- (not just "notices") to avoid clashing with
    the public listing page's own naming — this is homepage-specific markup
    (.c-section--notices), not the Zprávy výboru listing.
--}}
@props([
    'title',
    'subtitle' => '',
    'items' => [],
    'moreUrl' => '#',
    'moreText' => 'Všechny zprávy VV',
])

<section {{ $attributes->merge(['class' => 'c-section c-section--notices']) }}>
    <div class="c-container">
        <div class="c-section__header">
            <h2 class="c-section__title">{{ $title }}</h2>
            @if ($subtitle)
                <p class="c-section__subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="c-section__grid">
            @foreach ($items as $item)
                <x-notice-card
                    :title="$item->title"
                    :url="route('zpravodajstvi.vykonny-vybor.show', $item->slug)"
                    :tag-text="$item->is_important ? 'DŮLEŽITÉ' : ''"
                    :date="$item->date_text"
                />
            @endforeach
        </div>
        <div class="c-section__footer">
            <x-button :text="$moreText" :url="$moreUrl" variant="outline" />
        </div>
    </div>
</section>
