{{--
    <x-button :text :url :color :variant :size :has-arrow :icon :leading-icon :type :icon-size
              :target :aria-label :disabled />
    - url set -> renders <a>, otherwise <button type="{{ $type }}">. Mirrors
      ui/src/_includes/macros/button.njk.
    - color: "primary" (default) | "accent" | "dark"
    - variant: "solid" (default) | "outline" | "link"
    - size: "md" (default) | "sm" | "lg"
    - hasArrow: true (default, trailing icon) | false
    - icon: heroicon (mini) name shown when hasArrow — "chevron-right" (default)
    - leadingIcon: heroicon (mini) name shown BEFORE the text — "" (default, none)
    - Extra classes: pass a plain class="..." attribute on the component tag (merged via
      $attributes), no separate extraClass prop — see skills/web-component-guide.md.
--}}
@props([
    'text',
    'url' => '',
    'color' => 'primary',
    'variant' => 'solid',
    'size' => 'md',
    'hasArrow' => true,
    'icon' => 'chevron-right',
    'leadingIcon' => '',
    'type' => 'button',
    'iconSize' => 24,
    'target' => '',
    'ariaLabel' => '',
    'disabled' => false,
])

@php
    $classes = collect([
        'c-button',
        $color !== 'primary' ? "c-button--{$color}" : null,
        $variant !== 'solid' ? "c-button--{$variant}" : null,
        $size !== 'md' ? "c-button--{$size}" : null,
        $hasArrow ? 'c-button--with-arrow' : null,
    ])->filter()->implode(' ');
@endphp

@if ($url)
    <a
        href="{{ $url }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if ($target) target="{{ $target }}" rel="noopener noreferrer" @endif
        @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
        @if ($disabled) aria-disabled="true" tabindex="-1" @endif
    >
        @if ($leadingIcon)
            <x-dynamic-component :component="'heroicon-m-'.$leadingIcon" class="c-button_icon c-button_icon--leading" :width="$iconSize" :height="$iconSize" />
        @endif
        <span>{{ $text }}</span>
        @if ($hasArrow)
            <x-dynamic-component :component="'heroicon-m-'.$icon" class="c-button_icon" :width="$iconSize" :height="$iconSize" />
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
        @if ($disabled) disabled @endif
    >
        @if ($leadingIcon)
            <x-dynamic-component :component="'heroicon-m-'.$leadingIcon" class="c-button_icon c-button_icon--leading" :width="$iconSize" :height="$iconSize" />
        @endif
        <span>{{ $text }}</span>
        @if ($hasArrow)
            <x-dynamic-component :component="'heroicon-m-'.$icon" class="c-button_icon" :width="$iconSize" :height="$iconSize" />
        @endif
    </button>
@endif
