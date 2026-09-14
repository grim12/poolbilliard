{{--
    <x-tournaments :title :items :calendar-url :calendar-text />
    - items: collection/array of tournaments (title, url, tagText, tagColor, dateText,
      locationText, badge, soon). Mirrors ui/src/_includes/widgets/tournaments.njk.
    - NOTE: the "sledujte přímé přenosy... ČMBS TV" footer note (brand YouTube icon) is not
      ported yet — Simple Icons brand pack isn't a dependency here, only blade-heroicons.
      Add it when a brand-icon Blade package is introduced, see skills/web-component-guide.md.
--}}
@props([
    'title' => '',
    'items' => [],
    'calendarUrl' => '#',
    'calendarText' => 'Kompletní kalendář',
])

<section {{ $attributes->merge(['class' => 'c-section c-section--tournaments']) }}>
    <div class="c-container">
        <div class="c-section__header">
            <h2 class="c-section__title">{{ $title }}</h2>
        </div>
        <div class="c-section__grid">
            @foreach ($items as $item)
                <x-tournament-card
                    :title="$item->title"
                    :url="$item->url"
                    :tag-text="$item->tag_text"
                    :tag-color="$item->tag_color"
                    :date-text="$item->date_text"
                    :location-text="$item->location_text"
                    :badge="$item->badge"
                    :soon="$item->soon"
                />
            @endforeach
        </div>
        <div class="c-section__footer">
            <x-button :text="$calendarText" :url="$calendarUrl" variant="outline" />
        </div>
    </div>
</section>
