{{--
    Mirrors ui/src/zpravodajstvi/vykonny-vybor.njk — notice grid + real pagination + "Kontakt"
    sidebar info box (same news-grid sidebar layout as Novinky's "Důležité zprávy", just with
    a plain contact block instead of notice cards — no sidebarTagText/sidebarTitle here, same
    as ui/'s own newsGrid() call).
--}}
<x-layouts.app
    :title="__('Zprávy výboru').' — Poolbilliard'"
    :description="__('Zprávy a oznámení výkonného výboru Českého svazu poolbilliardu.')"
>
    <div class="bg-gradient-light">
        <x-news-header
            :title="__('Zprávy výboru')"
            :back-text="__('Zpět na novinky')"
            :back-url="\App\Support\Locale::route('novinky')"
            :show-search="false"
            class="pb-none"
        />

        <section class="c-section c-section--news-grid pt-none">
            <div class="c-container">
                <div class="c-section__grid c-news-grid__layout">
                    <div class="c-news-grid__main">
                        <div class="c-news-grid__cards c-news-grid__cards--notice">
                            @foreach ($notices as $notice)
                                <x-notice-card
                                    :title="$notice->title"
                                    :url="route('zpravodajstvi.vykonny-vybor.show', $notice->slug_cs)"
                                    :tag-text="$notice->is_important ? __('DŮLEŽITÉ') : ''"
                                    :date="$notice->date_text"
                                    :excerpt="$notice->excerpt"
                                    size="lg"
                                />
                            @endforeach
                        </div>

                        <x-pagination :paginator="$notices" />
                    </div>

                    <aside class="c-news-grid__sidebar">
                        <div class="c-news-grid__info">
                            <h3 class="c-news-grid__info-heading">
                                <x-heroicon-m-envelope width="18" height="18" />
                                {{ __('Kontakt') }}
                            </h3>
                            <p>{!! __('Pro jakékoliv informace od Sportovního svazu kontaktujte výkonný výbor na emailu :link', ['link' => '<a href="mailto:vvs.pool@cmbs.cz">vvs.pool@cmbs.cz</a>']) !!}</p>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>

    <x-newsletter />
</x-layouts.app>
