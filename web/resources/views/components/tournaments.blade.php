{{--
    <x-tournaments :title :items :calendar-url :calendar-text :stream-url />
    - items: collection of Tournament models (title, url, category relation, date_text/soon
      accessors, location_text, badge). Mirrors ui/src/_includes/widgets/tournaments.njk —
      category->name/color map onto the card's generic tagText/tagColor props.
    - stream-url: pass GeneralSettings::$cmbs_tv_url — the surrounding sentence is hardcoded
      (structural design copy, not editorial content), only the link destination is
      admin-editable. See skills/web-component-guide.md.
--}}
@props([
    'title' => '',
    'items' => [],
    'calendarUrl' => '#',
    'calendarText' => 'Kompletní kalendář',
    'streamUrl' => '#',
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
                    :tag-text="$item->category?->name"
                    :tag-color="$item->category?->color"
                    :date-text="$item->date_text"
                    :location-text="$item->location_text"
                    :badge="$item->badge"
                    :soon="$item->soon"
                />
            @endforeach
        </div>
        <div class="c-section__footer">
            <x-button :text="$calendarText" :url="$calendarUrl" variant="outline" />
            <p class="c-section__note">
                <x-brand-youtube width="20" height="20" />
                <span>Přímé přenosy z turnajů sledujte na <a href="{{ $streamUrl }}">ČMBS TV</a></span>
            </p>
        </div>
    </div>
</section>
