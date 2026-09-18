{{--
    <x-content-section :id :number :eyebrow :title :full-width :heading-level>
        {{ body }}
        <x-slot:aside>...</x-slot:aside>
        <x-slot:below>...</x-slot:below>
    </x-content-section>
    - Generic numbered content section: circular number badge + small eyebrow label + big title
      + divider, then a freeform body in the default slot. One shared component for every
      section of a longer explainer page (e.g. Soutěže) instead of bespoke markup per section.
    - number/eyebrow: both optional — omit `number` for an unnumbered section, omit both to
      drop the eyebrow row entirely. ui/'s macro also takes an `eyebrowColor` ("primary" |
      "accent") param, dropped here — no page ever passes "accent", and no `--accent` modifier
      CSS exists for `.c-section__eyebrow` in either `ui/` or `web/`, so it wasn't a real option.
    - The default slot always sits in a ~2/3-width main column, with the remaining ~1/3 reserved
      as a right-hand sidebar column — even when `aside` is empty, so every section keeps the
      same reading width. Unlike ui/'s Nunjucks macro (one `caller()` block only, so `aside`/
      `below` had to be pre-rendered strings built with `{% set %}`), Blade supports real named
      slots here — `aside`/`below` are just `<x-slot:aside>`/`<x-slot:below>`, no workaround
      needed.
    - below: same idea, but rendered full-width *below* the 2/3+1/3 grid — for content that
      shouldn't be squeezed into the main column.
    - id: optional anchor id, so <x-jump-nav> (or any other in-page link) can scroll to this
      section — see .c-section--content's scroll-margin-top, which clears the sticky jump-nav.
    - fullWidth: false (default) — body sits in the 2/3+1/3 grid described above | true — skip
      that grid entirely and render the body at the section's full width instead (`aside` is
      ignored in this mode).
    - headingLevel: "h2" (default) | "h3"
    Mirrors ui/src/_includes/macros/content-section.njk.
--}}
@props([
    'id' => '',
    'number' => '',
    'eyebrow' => '',
    'title' => '',
    'fullWidth' => false,
    'headingLevel' => 'h2',
])

<section {{ $attributes->merge(['class' => 'c-section c-section--content']) }} @if ($id) id="{{ $id }}" @endif>
    <div class="c-container">
        <header class="c-section__header">
            @if ($number || $eyebrow)
                <div class="c-section__eyebrow-row">
                    @if ($number)
                        <span class="c-section__number">{{ $number }}</span>
                    @endif
                    @if ($eyebrow)
                        <span class="c-section__eyebrow">{{ $eyebrow }}</span>
                    @endif
                </div>
            @endif
            <{{ $headingLevel }} class="c-section__title">{{ $title }}</{{ $headingLevel }}>
        </header>

        @if ($fullWidth)
            {{ $slot }}
        @else
            <div class="c-section__grid">
                <div class="c-section__body">
                    {{ $slot }}
                </div>
                @isset($aside)
                    <div class="c-section__aside">
                        {{ $aside }}
                    </div>
                @endisset
            </div>
        @endif

        @isset($below)
            <div class="c-section__below">
                {{ $below }}
            </div>
        @endisset
    </div>
</section>
