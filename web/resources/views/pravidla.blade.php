{{--
    Mirrors ui/src/pravidla.njk. $settings: App\Settings\PravidlaSettings — the 3 section
    headers (hero, myth intro, rule-cards intro). $myths: Myth collection, ordered. $ruleCards:
    RuleCard collection, ordered.
--}}
<x-layouts.app
    :title="\App\Support\Locale::field($settings, 'hero_title').' — Poolbilliard'"
    description="Pravidla poolbilliardu, vyvrácené mýty a přehled jednotlivých disciplín podle Českého svazu poolbilliardu."
>
    <x-page-hero
        :title="$settings->hero_title"
        :text="$settings->hero_text"
    />

    <section class="c-section">
        <div class="c-container">
            <div class="c-section__header">
                <h2 class="c-section__title">{{ $settings->myths_title }}</h2>
                <p class="c-section__subtitle max-w-3xl">{!! $settings->myths_subtitle !!}</p>
            </div>
            <x-myth-faq :items="$myths" id-prefix="mytus" />
        </div>
    </section>

    <section class="c-section c-section--rule-cards bg-gray-100 border-top border-bottom">
        <div class="c-container">
            <div class="c-section__header">
                <h2 class="c-section__title">{{ $settings->rule_cards_title }}</h2>
            </div>
            <div class="c-section__grid">
                @foreach ($ruleCards as $ruleCard)
                    <x-rule-card
                        :icon="$ruleCard->icon"
                        :image="$ruleCard->image_url"
                        :image-alt="$ruleCard->image_alt"
                        :title="$ruleCard->title"
                        :subtitle="$ruleCard->subtitle"
                        :text="$ruleCard->text"
                        :button-url="$ruleCard->button_url"
                    />
                @endforeach
            </div>
        </div>
    </section>

    <x-newsletter />
</x-layouts.app>
