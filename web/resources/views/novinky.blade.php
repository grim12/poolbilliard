{{--
    Mirrors ui/src/novinky.njk — article grid + real pagination + "Důležité zprávy" sidebar
    (real Notice query instead of ui/'s hand-picked static list).
--}}
<x-layouts.app
    title="Novinky — Poolbilliard"
    description="Aktuality a novinky ze světa Českého poolbilliardu — výsledky turnajů, reportáže a dění ve svazu."
>
    <div class="bg-gradient-light">
        <x-news-header title="Novinky" :categories="$categoryTabs" class="pb-none" />

        <section class="c-section c-section--news-grid">
            <div class="c-container">
                <div class="c-section__grid c-news-grid__layout">
                    <div class="c-news-grid__main">
                        <div class="c-news-grid__cards">
                            @foreach ($articles as $article)
                                <x-article-card
                                    :title="$article->title"
                                    :url="route('novinky.show', $article->slug_cs)"
                                    :image="$article->image_url"
                                    :tag-text="$article->category?->name"
                                    :tag-color="$article->category?->color"
                                    :date="$article->date_text"
                                    :excerpt="$article->excerpt"
                                />
                            @endforeach
                        </div>

                        <x-pagination :paginator="$articles" />
                    </div>

                    <aside class="c-news-grid__sidebar">
                        <x-tag text="Výkonný výbor" color="primary" variant="plain" size="sm" class="c-news-grid__sidebar-tag" />
                        <h2 class="h4 c-news-grid__sidebar-title">Důležité zprávy</h2>

                        <div class="c-news-grid__sidebar-list">
                            @foreach ($sidebarNotices as $notice)
                                <x-notice-card
                                    :title="$notice->title"
                                    :url="route('zpravodajstvi.vykonny-vybor.show', $notice->slug_cs)"
                                    :tag-text="$notice->is_important ? 'DŮLEŽITÉ' : ''"
                                    :date="$notice->date_text"
                                />
                            @endforeach
                        </div>

                        <x-button text="Archiv všech zpráv" :url="route('zpravodajstvi.vykonny-vybor')" variant="link" class="w-full" />
                    </aside>
                </div>
            </div>
        </section>
    </div>

    <x-newsletter />
</x-layouts.app>
