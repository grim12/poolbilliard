{{--
    <x-calendar-month :label :weekdays :weeks :prev-url :next-url :today-url />
    - label: month + year heading, e.g. "Září 2026"
    - weekdays: 7 short labels, Monday first
    - weeks: array of weeks; each week is an array of 7 cells (null for leading/trailing blanks)
      cell: ['day', 'iso', 'isToday', 'isWeekend', 'events' => [['title', 'url', 'tagColor']]]
    - prevUrl/nextUrl/todayUrl: real month navigation, computed in CalendarController.
    Mirrors ui/src/_includes/macros/calendar.njk.
--}}
@props([
    'label' => '',
    'weekdays' => ['PO', 'ÚT', 'ST', 'ČT', 'PÁ', 'SO', 'NE'],
    'weeks' => [],
    'prevUrl' => '#',
    'nextUrl' => '#',
    'todayUrl' => '#',
])

<div {{ $attributes->merge(['class' => 'c-calendar']) }}>
    <div class="c-calendar__header">
        <h2 class="c-calendar__title">{{ $label }}</h2>
        <div class="c-calendar__nav">
            <a href="{{ $prevUrl }}" class="c-calendar__nav-btn" aria-label="Předchozí měsíc">
                <x-heroicon-m-chevron-left width="18" height="18" />
            </a>
            <a href="{{ $todayUrl }}" class="c-calendar__today-btn">Dnes</a>
            <a href="{{ $nextUrl }}" class="c-calendar__nav-btn" aria-label="Následující měsíc">
                <x-heroicon-m-chevron-right width="18" height="18" />
            </a>
        </div>
    </div>

    <div class="c-calendar__grid">
        @foreach ($weekdays as $index => $weekday)
            <div @class(['c-calendar__weekday', 'c-calendar__weekday--weekend' => $index >= 5])>{{ $weekday }}</div>
        @endforeach
        @foreach ($weeks as $week)
            @foreach ($week as $cell)
                @if ($cell)
                    <div @class(['c-calendar__day', 'c-calendar__day--weekend' => $cell['isWeekend'], 'c-calendar__day--today' => $cell['isToday']])>
                        <span class="c-calendar__day-number">{{ $cell['day'] }}</span>
                        @if (count($cell['events']))
                            <div class="c-calendar__events">
                                @foreach ($cell['events'] as $event)
                                    <a href="{{ $event['url'] }}" class="c-calendar__event c-calendar__event--{{ $event['tagColor'] }}" aria-label="{{ $event['title'] }}" title="{{ $event['title'] }}">
                                        <span class="c-calendar__event-dot c-calendar__event-dot--{{ $event['tagColor'] }}"></span>
                                        <span class="c-calendar__event-label">{{ $event['title'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="c-calendar__day c-calendar__day--empty" aria-hidden="true"></div>
                @endif
            @endforeach
        @endforeach
    </div>
</div>
