{{--
    <x-leaderboard :title :entries :featured :link-text :link-url :max-entries :heading-level />
    - entries: array of {name, club} (club optional — team-only rankings like "Extraliga Týmů"
      have none), in rank order — rank itself isn't stored, just the array position (1-indexed
      in the loop below). Mirrors ui/src/_includes/macros/leaderboard.njk.
    - featured: true -> highlights rank #1 in accent red (the main national ranking) | false
      (default, dark navy)
    - maxEntries: null (default, show all stored entries) or an int — e.g. the homepage shows
      only the top 5 of each leaderboard's 10 stored entries, a future /souteze page would show
      all 10 by leaving this unset.
--}}
@props([
    'title',
    'entries' => [],
    'featured' => false,
    'linkText' => 'Detail série',
    'linkUrl' => '#',
    'maxEntries' => null,
    'headingLevel' => 'h3',
])

@php
    $visibleEntries = $maxEntries !== null ? array_slice($entries, 0, $maxEntries) : $entries;
@endphp

<div {{ $attributes->merge(['class' => 'c-leaderboard'.($featured ? ' c-leaderboard--featured' : '')]) }}>
    <div class="c-leaderboard__header">
        <{{ $headingLevel }} class="c-leaderboard__title">{{ $title }}</{{ $headingLevel }}>
    </div>
    <ol class="c-leaderboard__list">
        @foreach ($visibleEntries as $index => $entry)
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
