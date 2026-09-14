{{--
    Mirrors ui/src/zpravodajstvi/vykonny-vybor.njk (card grid only; the "Kontakt" info box
    ui/ places in newsGrid's sidebar is deferred — no sidebar layout exists yet, same scope
    cut as Novinky's "Důležité zprávy" sidebar).
--}}
<x-layouts.app title="Zprávy výboru — Poolbilliard">
    <div class="bg-gradient-light">
        <x-news-header
            title="Zprávy výboru"
            back-text="Zpět na novinky"
            :back-url="route('novinky')"
            :show-search="false"
            class="pb-none"
        />

        <section class="c-section c-section--news-grid pt-none">
            <div class="c-container">
                <div class="c-section__grid c-news-grid__cards c-news-grid__cards--notice">
                    @foreach ($notices as $notice)
                        <x-notice-card
                            :title="$notice->title"
                            :url="route('zpravodajstvi.vykonny-vybor.show', $notice->slug)"
                            :tag-text="$notice->is_important ? 'DŮLEŽITÉ' : ''"
                            :date="$notice->date_text"
                            :excerpt="$notice->excerpt"
                            size="lg"
                        />
                    @endforeach
                </div>

                <x-pagination :paginator="$notices" />
            </div>
        </section>
    </div>

    <x-newsletter />
</x-layouts.app>
