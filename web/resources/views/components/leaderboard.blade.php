{{--
    <x-leaderboard :title :entries :featured :link-text :link-url :heading-level />
    - entries: array of {name, club} (club optional — team-only rankings like "Extraliga Týmů"
      have none), in rank order — rank itself isn't stored, just the array position (1-indexed
      in the loop below). Mirrors ui/src/_includes/macros/leaderboard.njk.
    - featured: true -> highlights rank #1 in accent red (the main national ranking) | false
      (default, dark navy)
--}}
@props([
    'title',
    'entries' => [],
    'featured' => false,
    'linkText' => 'Detail série',
    'linkUrl' => '#',
    'headingLevel' => 'h3',
])

<div {{ $attributes->merge(['class' => 'c-leaderboard'.($featured ? ' c-leaderboard--featured' : '')]) }}>
    <div class="c-leaderboard__header">
        <{{ $headingLevel }} class="c-leaderboard__title">{{ $title }}</{{ $headingLevel }}>
    </div>
    <ol class="c-leaderboard__list">
        @foreach ($entries as $index => $entry)
            <li @class(['c-leaderboard__item', 'c-leaderboard__item--top' => $index === 0])>
                <span class="c-leaderboard__rank">{{ $index + 1 }}.</span>
                <span class="c-leaderboard__player">
                    <span class="c-leaderboard__name">{{ $entry['name'] }}</span>
                    @if (! empty($entry['club']))
                        <span class="c-leaderboard__club">{{ $entry['club'] }}</span>
                    @endif
                </span>
            </li>
        @endforeach
    </ol>
    <div class="c-leaderboard__footer">
        <x-button :text="$linkText" :url="$linkUrl" :color="$featured ? 'accent' : 'primary'" variant="link" />
    </div>
</div>
