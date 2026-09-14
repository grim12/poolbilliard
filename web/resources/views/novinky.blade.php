{{--
    Mirrors ui/src/novinky.njk — article grid + real pagination. The "Důležité zprávy"
    sidebar (reuses the Notice model) is deferred until Notice gets its own public surface,
    see skills/web-component-guide.md.
--}}
<x-layouts.app title="Novinky — Poolbilliard">
    <div class="bg-gradient-light">
        <x-news-header title="Novinky" :categories="$categoryTabs" class="pb-none" />

        <section class="c-section c-section--news-grid pt-none">
            <div class="c-container">
                <div class="c-section__grid c-news-grid__cards">
                    @foreach ($articles as $article)
                        <x-article-card
                            :title="$article->title"
                            :url="route('novinky.show', $article->slug)"
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
        </section>
    </div>

    <x-newsletter />
</x-layouts.app>
