{{--
    Mirrors ui/src/kalendar.njk — the hub for Tournament content (there's no separate /turnaje
    anymore, see skills/web-component-guide.md). $tournaments: Tournament collection (current +
    upcoming). $calendarMonth: month-grid data built by CalendarController — its prev/next/today
    links are real navigation, unlike the Svaz/Klub/Zahraniční checkbox filter below
    (intentionally inert, same "not wired up yet" treatment as /novinky's category tabs — it
    doesn't actually filter $tournaments or $calendarMonth). $recurringTournaments:
    RecurringTournament collection, eager-loaded herna. $settings: App\Settings\KalendarSettings
    — editorial text for the header (title/subtitle).
--}}
<x-layouts.app :title="$settings->title.' — Poolbilliard'">
    <div class="bg-gradient-light">
        <x-news-header
            :title="$settings->title"
            :subtitle="$settings->subtitle"
            :show-search="false"
            class="pb-none"
        >
            <x-slot:aside>
                <div class="c-check-pills" role="group" aria-label="Filtrovat podle typu akce">
                    <label class="c-check-pill c-check-pill--primary">
                        <input type="checkbox" class="c-check-pill__input" checked disabled />
                        <span class="c-check-pill__box"><x-heroicon-m-check width="12" height="12" /></span>
                        Svaz
                    </label>
                    <label class="c-check-pill c-check-pill--accent">
                        <input type="checkbox" class="c-check-pill__input" checked disabled />
                        <span class="c-check-pill__box"><x-heroicon-m-check width="12" height="12" /></span>
                        Klub
                    </label>
                    <label class="c-check-pill c-check-pill--gold">
                        <input type="checkbox" class="c-check-pill__input" disabled />
                        <span class="c-check-pill__box"><x-heroicon-m-check width="12" height="12" /></span>
                        Zahraniční
                    </label>
                </div>
            </x-slot:aside>
        </x-news-header>

        <section class="c-section pt-none">
            <div class="c-container">
                <div class="c-section__grid c-kalendar-layout">
                    <div class="c-kalendar-main">
                        <div class="c-kalendar-cards">
                            @foreach ($tournaments as $tournament)
                                <x-tournament-card
                                    :title="$tournament->title"
                                    :url="route('turnaj.show', $tournament)"
                                    :tag-text="$tournament->category?->name"
                                    :tag-color="$tournament->category?->color"
                                    :date-text="$tournament->date_text"
                                    :location-text="$tournament->location_text"
                                    :badge="$tournament->badge"
                                    :soon="$tournament->soon"
                                />
                            @endforeach
                        </div>

                        @if ($tournaments->isEmpty())
                            <x-no-results
                                title="Žádné akce neodpovídají zvoleným filtrům."
                                icon="calendar-days"
                                class="mt-3 lg:mt-4"
                            />
                        @endif
                    </div>

                    <aside class="c-kalendar-aside">
                        <x-calendar-month
                            :label="$calendarMonth['label']"
                            :weekdays="$calendarMonth['weekdays']"
                            :weeks="$calendarMonth['weeks']"
                            :prev-url="$calendarMonth['prevUrl']"
                            :next-url="$calendarMonth['nextUrl']"
                            :today-url="$calendarMonth['todayUrl']"
                        />

                        @if ($recurringTournaments->isNotEmpty())
                            <x-recurring-tournaments
                                :items="$recurringTournaments->map(fn ($item) => [
                                    'frequency' => $item->frequency,
                                    'title' => $item->title,
                                    'location' => $item->location_text,
                                    'url' => route('pravidelny-turnaj.show', $item),
                                ])->all()"
                                :buttons="[
                                    ['text' => 'Kulečníkové kluby', 'url' => route('kluby')],
                                    ['text' => 'Kulečníkové herny', 'url' => route('herny')],
                                ]"
                            />
                        @endif

                        <x-calendar-sources
                            :sources="[
                                ['title' => 'ČMBS kalendář', 'subtitle' => 'Svazové soutěže a akce', 'url' => '#'],
                                ['title' => 'EPBF kalendář', 'subtitle' => 'European Pocket Billiard Federation', 'url' => '#'],
                                ['title' => 'EEBC kalendář', 'subtitle' => 'East European Billiard Council', 'url' => '#'],
                                ['title' => 'Matchroom kalendář', 'subtitle' => 'Matchroom Pool', 'url' => '#'],
                            ]"
                        />
                    </aside>
                </div>
            </div>
        </section>
    </div>

    <x-newsletter />
</x-layouts.app>
