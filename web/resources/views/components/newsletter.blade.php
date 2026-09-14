{{--
    <x-newsletter :tag-text :title :subtitle :placeholder :button-text :legal-text
                  :legal-link-text :legal-link-url :form-action />
    - Email capture section. Primary-color gradient background, light text via .t-dark.
    Mirrors ui/src/_includes/widgets/newsletter.njk.
--}}
@props([
    'tagText' => '',
    'title' => 'Newsletter',
    'subtitle' => 'Nenech si ujít žádnou novinku. Přihlas se k odběru newsletteru a dostávej přehled turnajů, výsledků a zpráv ze světa českého poolbilliardu.',
    'placeholder' => 'Vložte svůj e-mail',
    'buttonText' => 'Odebírat',
    'legalText' => 'Přihlášením k odběru vyjadřujete',
    'legalLinkText' => 'souhlas se zpracováním osobních údajů',
    'legalLinkUrl' => '#',
    'formAction' => '#',
])

<section {{ $attributes->merge(['class' => 'c-section c-section--newsletter t-dark']) }}>
    <div class="c-container">
        <div class="c-section__header">
            @if ($tagText)
                <x-tag :text="$tagText" color="primary" variant="subtle" class="c-newsletter__tag" />
            @endif
            <h2 class="c-section__title">{{ $title }}</h2>
            @if ($subtitle)
                <p class="c-section__subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        <form class="c-newsletter__form" action="{{ $formAction }}" method="post">
            <label class="sr-only" for="newsletter-email">{{ $placeholder }}</label>
            <div class="c-newsletter__field">
                <span class="c-newsletter__icon" aria-hidden="true">
                    <x-heroicon-m-envelope width="18" height="18" />
                </span>
                <input class="c-input c-newsletter__input" type="email" id="newsletter-email" name="email" placeholder="{{ $placeholder }}" autocomplete="email" required />
            </div>
            <x-button :text="$buttonText" type="submit" :has-arrow="false" class="c-newsletter__submit shrink-0" />
        </form>
        <p class="c-newsletter__legal">{{ $legalText }} <a href="{{ $legalLinkUrl }}">{{ $legalLinkText }}</a>.</p>
    </div>
</section>
