{{--
    <x-page-hero :title :subtitle :text :stats :heading-level />
    - Full-width dark gradient hero for a content/explainer page (e.g. Soutěže) — title +
      subtitle + description on the left, optional 2x2 stat tiles on the right.
    - stats: array of ['value', 'label'] — up to 4 tiles, omitted entirely when empty.
    - headingLevel: "h1" (default) | "h2" — use h2 if this hero isn't the page's main heading.
    Mirrors ui/src/_includes/widgets/page-hero.njk.
--}}
@props([
    'title' => '',
    'subtitle' => '',
    'text' => '',
    'stats' => [],
    'headingLevel' => 'h1',
])

<section {{ $attributes->merge(['class' => 'c-section c-section--page-hero t-dark']) }}>
    <div class="c-container c-section__inner">
        <div class="c-section__content">
            <{{ $headingLevel }} class="c-section__title">{{ $title }}</{{ $headingLevel }}>
            @if ($subtitle)
                <p class="c-section__subtitle">{{ $subtitle }}</p>
            @endif
            @if ($text)
                <p class="c-section__text">{{ $text }}</p>
            @endif
        </div>

        @if (count($stats))
            <div class="c-section__stats">
                @foreach ($stats as $stat)
                    <div class="c-section__stat">
                        <p class="c-section__stat-value">{{ $stat['value'] }}</p>
                        <p class="c-section__stat-label">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
