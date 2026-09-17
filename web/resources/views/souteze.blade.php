{{--
    Mirrors ui/src/souteze.njk. $settings: App\Settings\SoutezeSettings — hero title/subtitle/
    text/stats. $sections: CompetitionSection collection, ordered — each numbered section below
    the hero (number is the loop position, not a stored field). $jumpNavItems: built in
    SoutezeController from the sections + a trailing "Žebříčky" pill. $leaderboards:
    Leaderboard collection — same model/component the homepage uses, but full (no max-entries),
    since this is the real, complete rankings listing.
--}}
<x-layouts.app
    :title="\App\Support\Locale::field($settings, 'title').' — Poolbilliard'"
    :description="__('Přehled soutěží a žebříčků Českého svazu poolbilliardu.')"
>
    <x-page-hero
        :title="$settings->title"
        :subtitle="$settings->subtitle"
        :text="$settings->text"
        :stats="$settings->stats"
    />

    <x-jump-nav :items="$jumpNavItems" />

    @foreach ($sections as $index => $section)
        <x-content-section
            :id="$section->anchor"
            :number="$index + 1"
            :eyebrow="$section->eyebrow"
            :title="$section->title"
            class="{{ $index % 2 === 1 ? 'bg-gray-100 border-top border-bottom' : '' }}"
        >
            {!! $section->body !!}

            @if ($section->aside)
                <x-slot:aside>
                    <div class="c-section__card">
                        {!! $section->aside !!}
                    </div>
                </x-slot:aside>
            @endif

            @if ($section->below)
                <x-slot:below>
                    <div class="c-section__card">
                        {!! $section->below !!}
                    </div>
                </x-slot:below>
            @endif
        </x-content-section>
    @endforeach

    <x-leaderboards
        id="zebricky"
        :title="__('Žebříčky')"
        :subtitle="__('Aktuální pořadí ve všech sériích a kategoriích — TOP 10 hráčů.')"
        :items="$leaderboards"
        :more-text="__('Kalendář soutěží')"
        :more-url="\App\Support\Locale::route('kalendar')"
    />

    <x-newsletter />
</x-layouts.app>
