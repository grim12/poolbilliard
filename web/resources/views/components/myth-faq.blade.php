{{--
    <x-myth-faq :items :id-prefix />
    - Myth/fact list as an FAQ-style accordion — the myth is always visible as the closed-state
      question (so it still reads as a list to skim), click reveals the correct rule as the
      answer. Reuses the .c-faq component's CSS as-is (question/answer/chevron/panel classes,
      plus the .c-faq__badge element it supports) — no separate CSS, same as
      components/faq.blade.php but with a "Mýtus"/"Správně" badge instead of a numbered index.
    - items: Myth collection (correct_text may contain trusted HTML — printed unescaped, same
      as ui/'s `| safe` filter).
    - The first item starts open (aria-expanded="true") so the answer pattern is obvious at a
      glance — resources/js/app.js already expands any pre-opened item on load (ported from
      ui/'s main.js well before this component existed).
    Mirrors ui/src/_includes/macros/myth-faq.njk.
--}}
@props([
    'items' => [],
    'idPrefix' => 'myth-faq',
])

@if (count($items))
    <div {{ $attributes->merge(['class' => 'c-faq']) }}>
        @foreach ($items as $index => $item)
            <div class="c-faq__item">
                <button type="button" class="c-faq__question" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="{{ $idPrefix }}-panel-{{ $index + 1 }}" data-faq-toggle>
                    <span class="c-faq__badge c-faq__badge--myth">
                        <x-heroicon-m-exclamation-triangle width="12" height="12" />
                        {{ __('Mýtus') }}
                    </span>
                    <span class="c-faq__question-text">„{{ $item->myth_text }}“</span>
                    <x-heroicon-m-chevron-down class="c-faq__chevron" width="20" height="20" />
                </button>
                <div class="c-faq__panel" id="{{ $idPrefix }}-panel-{{ $index + 1 }}" data-faq-panel>
                    <div class="c-faq__answer">
                        <span class="c-faq__badge c-faq__badge--correct">
                            <x-heroicon-m-check-circle width="12" height="12" />
                            {{ __('Správně') }}
                        </span>
                        {{-- correct_text is RichEditor-authored HTML (own <p> tags already) —
                             no wrapping <p>, same pitfall as about_text/description elsewhere. --}}
                        {!! $item->correct_text !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
