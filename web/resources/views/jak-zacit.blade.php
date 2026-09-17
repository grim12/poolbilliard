{{--
    Mirrors ui/src/jak-zacit.njk. $settings: App\Settings\JakZacitSettings — hero title/text,
    the 4 "Kde začít" feature cards, and the closing "Připraveni začít?"/match-form headers.
    $sections: JakZacitSection collection, ordered — one per audience path (Úplný začátečník,
    Rekreační hráč, Rodič), each with its own steps/aside/FAQ. $jumpNavItems: built in
    JakZacitController from the sections' nav_label/anchor plus a trailing "Chci se zlepšit"
    pill pointing at the match-form section below them.
--}}
<x-layouts.app
    :title="\App\Support\Locale::field($settings, 'hero_title').' — Poolbilliard'"
    :description="__('Chcete začít hrát poolbilliard? Zjistěte, jak najít klub, přihlásit se k prvnímu turnaji a zorientovat se v soutěžích.')"
>
    <x-page-hero :title="$settings->hero_title" :text="$settings->hero_text" />

    <x-jump-nav :items="$jumpNavItems" />

    <x-content-section :eyebrow="__('Kde začít')" :title="__('Která situace vás nejlépe vystihuje?')" full-width>
        <div class="c-feature-cards">
            @foreach ($settings->feature_cards as $card)
                <x-feature-card
                    :icon="$card['icon']"
                    :title="$card['title']"
                    :text="$card['text']"
                    :link-text="$card['link_text']"
                    :link-url="$card['link_url']"
                />
            @endforeach
        </div>
    </x-content-section>

    @foreach ($sections as $index => $section)
        <x-content-section
            :id="$section->anchor"
            :eyebrow="$section->eyebrow"
            :title="$section->title"
            class="{{ $index % 2 === 0 ? 'bg-gray-100 border-top border-bottom' : '' }}"
        >
            {!! $section->intro !!}

            <x-steps :items="$section->steps" class="mt-6" />

            <x-faq
                class="mt-6 sm:mt-8"
                :id-prefix="$section->anchor"
                :title="$section->faq_title"
                :items="$section->faqItems()->map(fn ($item) => ['question' => $item->question, 'answer' => $item->answer])->all()"
            />

            <x-slot:aside>
                <x-info-panel
                    :title="$section->aside_panel_title"
                    :text="$section->aside_panel_text"
                    :button-text="$section->aside_panel_button_text"
                    :button-url="$section->aside_panel_resolved_url"
                    heading-level="h3"
                />
                <div class="c-section__card mt-6">
                    <p class="c-section__eyebrow mb-2">{{ $section->aside_card_eyebrow }}</p>
                    <h3 class="mb-3">{{ $section->aside_card_title }}</h3>
                    <p>{{ $section->aside_card_text }}</p>
                    <x-button :text="$section->aside_card_button_text" :url="$section->aside_card_resolved_url" variant="link" size="sm" />
                </div>
            </x-slot:aside>
        </x-content-section>
    @endforeach

    <x-form-section id="doporuceni" :eyebrow="$settings->match_form_eyebrow" :title="$settings->match_form_title">
        <x-match-form :text="$settings->match_form_text" />
    </x-form-section>

    <section class="c-section bg-gray-100 border-top border-bottom text-center">
        <div class="c-container">
            <h2 class="mb-3">{{ $settings->cta_title }}</h2>
            <p class="p--lg max-w-2xl mx-auto mb-8">{{ $settings->cta_text }}</p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <x-button :text="__('Najít klub')" :url="\App\Support\Locale::route('kluby')" variant="outline" size="sm" />
                <x-button :text="__('Najít hernu')" :url="\App\Support\Locale::route('herny')" variant="outline" size="sm" />
                <x-button :text="__('Najít turnaj')" :url="\App\Support\Locale::route('kalendar')" variant="outline" size="sm" />
                <x-button :text="__('Chci se zlepšit')" url="#doporuceni" variant="outline" size="sm" />
            </div>
        </div>
    </section>

    <x-newsletter />
</x-layouts.app>
