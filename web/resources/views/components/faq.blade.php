{{--
    <x-faq :items :title :id-prefix />
    - items: array of ['question' => string, 'answer' => string (raw HTML allowed, e.g. <a>
      links — printed unescaped, same as ui/'s `| safe` filter; trusted admin-authored content,
      see Filament's RichEditor on FaqItemResource)]
    - idPrefix: short unique slug for this group's panel ids (a page can have more than one
      faq() group). Mirrors ui/src/_includes/macros/faq.njk. Accordion behavior (only one
      question open at a time per group) is wired up in resources/js/app.js.
--}}
@props([
    'items' => [],
    'title' => '',
    'idPrefix' => 'faq',
])

@if (count($items))
    <div {{ $attributes }}>
        @if ($title)
            <h3 class="mb-4">{{ $title }}</h3>
        @endif

        <div class="c-faq">
            @foreach ($items as $item)
                <div class="c-faq__item">
                    <button type="button" class="c-faq__question" aria-expanded="false" aria-controls="{{ $idPrefix }}-panel-{{ $loop->iteration }}" data-faq-toggle>
                        <span class="c-faq__index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="c-faq__question-text">{!! $item['question'] !!}</span>
                        <x-heroicon-m-chevron-down class="c-faq__chevron" width="20" height="20" />
                    </button>
                    <div class="c-faq__panel" id="{{ $idPrefix }}-panel-{{ $loop->iteration }}" data-faq-panel>
                        <div class="c-faq__answer">
                            <p>{!! $item['answer'] !!}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
