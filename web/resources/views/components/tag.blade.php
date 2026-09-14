{{--
    <x-tag :text :url :color :variant :size :target :aria-label />
    - url set -> renders <a>, otherwise <span>. Mirrors ui/src/_includes/macros/tag.njk.
    - color: "primary" (default) | "accent" | "dark" | "gray" | "gold" | "gold-light"
    - variant: "solid" (default) | "subtle" | "plain" | "outline"
    - size: "md" (default) | "sm" | "lg"
    - Extra classes: pass a plain class="..." attribute on the component tag (merged via
      $attributes), no separate extraClass prop — see skills/web-component-guide.md.
--}}
@props([
    'text',
    'url' => '',
    'color' => 'primary',
    'variant' => 'solid',
    'size' => 'md',
    'target' => '',
    'ariaLabel' => '',
])

@php
    $classes = collect([
        'c-tag',
        $color !== 'primary' ? "c-tag--{$color}" : null,
        $variant !== 'solid' ? "c-tag--{$variant}" : null,
        $size !== 'md' ? "c-tag--{$size}" : null,
    ])->filter()->implode(' ');
@endphp

@if ($url)
    <a
        href="{{ $url }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if ($target) target="{{ $target }}" rel="noopener noreferrer" @endif
        @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    >{{ $text }}</a>
@else
    <span {{ $attributes->merge(['class' => $classes]) }} @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif>{{ $text }}</span>
@endif
