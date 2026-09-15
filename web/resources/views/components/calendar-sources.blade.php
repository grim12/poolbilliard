{{--
    <x-calendar-sources :title :sources />
    - White sidebar card: plain list of external calendars this page's data is fed from.
    - sources: array of ['title', 'subtitle', 'url']
    - Static reference list (structural content, like GeneralSettings::$cmbs_tv_url's note) —
      not admin-editable for now, see resources/views/kalendar.blade.php.
    Mirrors ui/src/_includes/macros/calendar-sources.njk.
--}}
@props([
    'title' => 'Zdrojové kalendáře',
    'sources' => [],
])

<div {{ $attributes->merge(['class' => 'c-calendar-sources']) }}>
    <h3 class="c-calendar-sources__title">
        <x-heroicon-m-calendar-days width="18" height="18" />
        {{ $title }}
    </h3>
    <div class="c-calendar-sources__list">
        @foreach ($sources as $source)
            <a href="{{ $source['url'] }}" class="c-calendar-sources__item" target="_blank" rel="noopener noreferrer" aria-label="{{ $source['title'] }}">
                <span class="c-calendar-sources__body">
                    <span class="c-calendar-sources__name">{{ $source['title'] }}</span>
                    <span class="c-calendar-sources__desc">{{ $source['subtitle'] }}</span>
                </span>
                <span class="c-calendar-sources__external" aria-hidden="true">
                    <x-heroicon-m-arrow-top-right-on-square width="16" height="16" />
                </span>
            </a>
        @endforeach
    </div>
</div>
