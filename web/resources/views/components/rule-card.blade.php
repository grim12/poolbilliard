{{--
    <x-rule-card :icon :image :image-alt :title :subtitle :text :button-text :button-url />
    - Card for "Pravidla podle disciplíny": icon/image + title + small uppercase subtitle, body
      text, outline button pinned to the bottom.
    - image (optional): uploaded discipline icon, takes priority over `icon` when set. ui/'s
      macro also has a "balls" pictogram fallback variant — dropped here, see
      App\Models\RuleCard's doc comment for why.
    Mirrors ui/src/_includes/macros/rule-card.njk.
--}}
@props([
    'icon' => '',
    'image' => '',
    'imageAlt' => '',
    'title' => '',
    'subtitle' => '',
    'text' => '',
    'buttonText' => 'Zobrazit pravidla',
    'buttonUrl' => '#',
])

<div {{ $attributes->merge(['class' => 'c-rule-card']) }}>
    <div class="c-rule-card__top">
        @if ($image)
            <span class="c-rule-card__icon c-rule-card__icon--image" aria-hidden="true">
                <img src="{{ $image }}" alt="{{ $imageAlt }}" loading="lazy" />
            </span>
        @elseif ($icon)
            <span class="c-rule-card__icon c-rule-card__icon--badge" aria-hidden="true">
                <x-dynamic-component :component="'heroicon-m-'.$icon" width="28" height="28" />
            </span>
        @endif
        <div class="c-rule-card__heading">
            <h3 class="c-rule-card__title">{{ $title }}</h3>
            @if ($subtitle)
                <p class="c-rule-card__subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if ($text)
        {{-- $text is RichEditor-authored HTML (own <p> tags already), see App\Models\RuleCard —
             no wrapping <p>, same pitfall as about_text/description elsewhere in the project. --}}
        <div class="c-rule-card__text">{!! $text !!}</div>
    @endif
    <x-button :text="$buttonText" :url="$buttonUrl" variant="outline" size="sm" class="mt-auto self-start" />
</div>
