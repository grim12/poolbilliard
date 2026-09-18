{{--
    <x-form-section :id :eyebrow :title :heading-level>
        {{ body }}
    </x-form-section>
    - Narrow, centered section for a single form (or any other narrow-column content) — same
      reading-width treatment as <x-article-content>'s .c-section__inner. Distinct from
      <x-content-section>'s 2/3+1/3 reading-column + sidebar layout, which doesn't fit a single
      centered form. Mirrors ui/src/_includes/widgets/form-section.njk.
    - headingLevel: "h2" (default) | "h3"
--}}
@props([
    'id' => '',
    'eyebrow' => '',
    'title' => '',
    'headingLevel' => 'h2',
])

<section {{ $attributes->merge(['class' => 'c-section c-section--form']) }} @if ($id) id="{{ $id }}" @endif>
    <div class="c-container c-section__inner">
        <header class="c-section__header">
            @if ($eyebrow)
                <p class="c-section__eyebrow">{{ $eyebrow }}</p>
            @endif
            <{{ $headingLevel }} class="c-section__title">{{ $title }}</{{ $headingLevel }}>
        </header>
        {{ $slot }}
    </div>
</section>
