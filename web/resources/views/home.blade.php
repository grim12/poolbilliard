{{--
    Mirrors ui/src/index.njk. Every section either queries real data with a fixed count (not
    admin-configurable) or reads from HomepageSettings ($settings) — see
    skills/web-component-guide.md for the full breakdown of what's automatic vs. editable.
--}}
<x-layouts.app title="Poolbilliard — Český svaz poolbilliardu">
    <h1 class="sr-only">Poolbilliard — Český svaz poolbilliardu</h1>

    <div class="c-hp-intro bg-gradient-light">
        @if ($featuredArticle)
            <x-hp-featured-articles
                :featured="$featuredArticle"
                :items="$featuredArticleItems"
                :more-text="$settings->featured_articles_button_text"
                :more-url="route('novinky')"
                class="pb-none"
            />
        @endif

        <x-hp-notices
            :title="$settings->notices_title"
            :subtitle="$settings->notices_subtitle"
            :items="$notices"
            :more-text="$settings->notices_button_text"
            :more-url="route('zpravodajstvi.vykonny-vybor')"
        />

        @if ($banner1)
            <section class="c-section c-section--event-banner pt-none">
                <div class="c-container">
                    <x-banner :banner="$banner1" />
                </div>
            </section>
        @endif
    </div>

    {{--
        Tournaments and Leaderboards both have bg-gray-100/border-top/border-bottom — normally
        two separate gray sections, but when Banner 2 is empty they become directly adjacent
        (no white section between them). Per skills/ui-component-guide.md's documented pattern,
        two same-background sections back to back must drop the border on their shared inner
        seam (Tournaments' border-bottom, Leaderboards' border-top — each keeps the border on
        its own outer edge) and the *following* section (Leaderboards) drops its top padding,
        not the preceding one, to avoid a doubled gap between them.
    --}}
    <x-tournaments
        :title="$settings->tournaments_title"
        :items="$tournaments"
        :calendar-text="$settings->tournaments_button_text"
        calendar-url="/kalendar"
        :stream-url="$cmbsTvUrl"
        @class(['bg-gray-100', 'border-top', 'border-bottom' => $banner2])
    />

    @if ($banner2)
        <section class="c-section c-section--cta bg-white">
            <div class="c-container">
                <x-banner :banner="$banner2" />
            </div>
        </section>
    @endif

    <x-leaderboards
        :title="$settings->leaderboards_title"
        :subtitle="$settings->leaderboards_subtitle"
        :items="$leaderboards"
        :more-text="$settings->leaderboards_button_text"
        more-url="/souteze"
        :max-entries="5"
        @class(['bg-gray-100', 'border-bottom', 'border-top' => $banner2, 'pt-none' => ! $banner2])
    />

    @if ($linkTiles->isNotEmpty())
        <x-link-tiles :items="$linkTiles" class="bg-white" />
    @endif

    <x-hp-partners
        :title="$settings->partners_title"
        :items="$partners"
        :more-text="$settings->partners_button_text"
        :more-url="route('partneri')"
        class="bg-white pt-none"
    />

    <x-newsletter />
</x-layouts.app>
