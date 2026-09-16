{{--
    <x-documents :title :years />
    - "Dokumenty sekce ..." browser: a row of year tabs above an accordion of document
      categories. Clicking a tab swaps which year's panel is visible (data-doc-tab/
      data-doc-panel, wired in resources/js/app.js); within a panel, each category is its own
      .c-faq accordion group (faq.css/app.js, unmodified) — only the count badge and file grid
      are specific to this widget, see 02_components/section/documents.css.
      Mirrors ui/src/_includes/widgets/documents.njk.
    - years: array of ['label', 'active', 'groups' => [['title', 'count', 'files' => [['name',
      'meta', 'url']]]]]. Exactly one year should have active=true (which tab/panel shows
      first) — see App\Http\Controllers\SvazController::buildDocumentYears().
--}}
@props([
    'title' => '',
    'years' => [],
])

<section {{ $attributes->merge(['class' => 'c-section c-section--documents']) }}>
    <div class="c-container">
        <div class="c-section__header">
            <h2 class="c-section__title">{{ $title }}</h2>
        </div>

        @if (count($years))
            <div class="c-doc-tabs" role="tablist" aria-label="Rok dokumentů" data-doc-tabs>
                @foreach ($years as $year)
                    <button type="button" class="c-doc-tabs__pill @if ($year['active']) is-active @endif" role="tab" aria-selected="{{ $year['active'] ? 'true' : 'false' }}" aria-controls="doc-panel-{{ $loop->iteration }}" data-doc-tab>{{ $year['label'] }}</button>
                @endforeach
            </div>

            @foreach ($years as $year)
                @php($yearPanel = $loop->iteration)
                <div class="c-faq c-doc-groups" id="doc-panel-{{ $yearPanel }}" role="tabpanel" @if (! $year['active']) hidden @endif data-doc-panel>
                    @foreach ($year['groups'] as $group)
                        <div class="c-faq__item">
                            <button type="button" class="c-faq__question" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="doc-panel-{{ $yearPanel }}-group-{{ $loop->iteration }}" data-faq-toggle>
                                <span class="c-faq__question-text">{{ $group['title'] }} <span class="c-doc-groups__count">({{ $group['count'] }})</span></span>
                                <x-heroicon-m-chevron-down class="c-faq__chevron" width="20" height="20" />
                            </button>
                            <div class="c-faq__panel" id="doc-panel-{{ $yearPanel }}-group-{{ $loop->iteration }}" data-faq-panel>
                                <div class="c-doc-groups__files">
                                    @foreach ($group['files'] as $file)
                                        <a class="c-doc-file" href="{{ $file['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">
                                            <x-heroicon-m-document-text class="c-doc-file__icon" width="20" height="20" />
                                            <span class="c-doc-file__name">{{ $file['name'] }}</span>
                                            <span class="c-doc-file__meta">{{ $file['meta'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
</section>
