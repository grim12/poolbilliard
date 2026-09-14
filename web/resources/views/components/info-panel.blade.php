{{--
    <x-info-panel :tag-text :title :text :items :foot-text :button-text :button-url
                  :heading-level />
    - Dark gradient info card. Mirrors ui/src/_includes/macros/info-panel.njk.
    - items: array of ['icon' => heroicon name, 'text' => string] — short checklist rows.
    - headingLevel: "h2" (default) | "h3" | "h4"
--}}
@props([
    'tagText' => '',
    'title' => '',
    'text' => '',
    'items' => [],
    'footText' => '',
    'buttonText' => '',
    'buttonUrl' => '#',
    'headingLevel' => 'h2',
])

<div {{ $attributes->merge(['class' => 'c-info-panel t-dark']) }}>
    @if ($tagText)
        <x-tag :text="$tagText" color="gold-light" variant="subtle" size="sm" class="c-info-panel__tag" />
    @endif

    <{{ $headingLevel }} class="c-info-panel__title">{{ $title }}</{{ $headingLevel }}>

    @if ($text)
        <p class="c-info-panel__text">{{ $text }}</p>
    @endif

    @if (count($items))
        <ul class="c-info-panel__list">
            @foreach ($items as $item)
                <li class="c-info-panel__item">
                    <span class="c-info-panel__item-icon" aria-hidden="true">
                        <x-dynamic-component :component="'heroicon-m-'.$item['icon']" width="16" height="16" />
                    </span>
                    {{ $item['text'] }}
                </li>
            @endforeach
        </ul>
    @endif

    @if ($footText)
        <p class="c-info-panel__foot">{{ $footText }}</p>
    @endif

    @if ($buttonText)
        <x-button :text="$buttonText" :url="$buttonUrl" variant="outline" size="sm" class="self-start mt-auto" />
    @endif
</div>
