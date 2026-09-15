{{--
    <x-alert :icon :title :text :color :size />
    - Horizontal notice card: icon badge + title + text — e.g. "Nábor uzavřen"/"Nábor otevřen"
      on the club detail page. Mirrors ui/src/_includes/macros/alert.njk.
    - color: "gray" (default, neutral) | "primary" | "accent" | "gold"
    - size: "md" (default) | "lg" — bigger icon badge + title, for a more prominent notice
--}}
@props([
    'icon' => 'information-circle',
    'title' => '',
    'text' => '',
    'color' => 'gray',
    'size' => 'md',
])

@php
    $classes = collect([
        'c-alert',
        $color !== 'gray' ? "c-alert--{$color}" : null,
        $size !== 'md' ? "c-alert--{$size}" : null,
    ])->filter()->implode(' ');
    $iconSize = $size === 'lg' ? 28 : 20;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} role="note">
    <span class="c-alert__icon" aria-hidden="true">
        <x-dynamic-component :component="'heroicon-m-'.$icon" :width="$iconSize" :height="$iconSize" />
    </span>
    <div class="c-alert__body">
        @if ($title)
            <p class="c-alert__title">{{ $title }}</p>
        @endif
        @if ($text)
            <p class="c-alert__text">{!! $text !!}</p>
        @endif
    </div>
</div>
