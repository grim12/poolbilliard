{{--
    <x-banner :banner :heading-level />
    - banner: a Banner model (title, text rich-HTML, image_url, tag_text, meta_text, color enum,
      buttons array of {text: {cs, en}, url, variant}). Mirrors ui/src/_includes/macros/banner.njk —
      color drives the tag and every button unless a button has its own variant (buttons don't
      store a per-button color override, only variant, see skills/web-component-guide.md).
--}}
@props([
    'banner',
    'headingLevel' => 'h2',
])

@php
    $color = $banner->color?->value ?? 'accent';
@endphp

<div {{ $attributes->merge(['class' => 'c-banner t-dark']) }}>
    @if ($banner->image_url)
        <div class="c-banner__media">
            <img src="{{ $banner->image_url }}" alt="" loading="lazy" />
        </div>
    @endif
    <div class="c-banner__overlay"></div>
    <div class="c-banner__content">
        @if ($banner->tag_text || $banner->meta_text)
            <div class="c-banner__meta">
                @if ($banner->tag_text)
                    <x-tag :text="$banner->tag_text" :color="$color" variant="solid" />
                @endif
                @if ($banner->meta_text)
                    <span class="c-banner__meta-text">{{ $banner->meta_text }}</span>
                @endif
            </div>
        @endif
        <{{ $headingLevel }} class="c-banner__title h1--big">{{ $banner->title }}</{{ $headingLevel }}>
        @if ($banner->text)
            <div class="c-banner__text p--lg">{!! $banner->text !!}</div>
        @endif
        @if (! empty($banner->buttons))
            <div class="c-banner__actions">
                @foreach ($banner->buttons as $button)
                    <x-button
                        :text="$button['text'][app()->getLocale()] ?? $button['text']['cs'] ?? ''"
                        :url="\App\Support\InternalLink::resolveFromArray($button)"
                        :color="$color"
                        :variant="$button['variant'] ?? 'solid'"
                    />
                @endforeach
            </div>
        @endif
    </div>
</div>
