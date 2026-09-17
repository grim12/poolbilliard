{{--
    Mirrors ui/src/faq.njk. Site chrome (header/footer) now wired via <x-layouts.app>;
    pageHero() and linkTiles() aren't ported yet — see skills/web-component-guide.md.
--}}
<x-layouts.app
    :title="__('Časté dotazy').' — Poolbilliard'"
    :description="__('Odpovědi na nejčastější dotazy o poolbilliardu, registraci, soutěžích a členství v Českém svazu poolbilliardu.')"
>
    <section class="c-section c-section--faq">
        <div class="c-container">
            <div class="c-section__grid">
                <div class="c-section__body">
                    <h2>{{ __('Otázky a odpovědi') }}</h2>
                    <x-faq
                        id-prefix="faq"
                        :items="$items->map(fn ($item) => ['question' => $item->question, 'answer' => $item->answer])->all()"
                    />
                </div>

                <aside class="c-section__aside">
                    <div class="c-faq-contact">
                        <p class="c-section__eyebrow">{{ __('Kontakt') }}</p>
                        <div class="c-faq-contact__list">
                            <a class="c-faq-contact__link" href="mailto:kontakt@poolbilliard.cz">
                                <x-heroicon-m-envelope width="18" height="18" />
                                kontakt@poolbilliard.cz
                            </a>
                            <a class="c-faq-contact__link" href="/sportovni-svaz/">
                                <x-heroicon-m-user-group width="18" height="18" />
                                {{ __('Kontakty na výkonný výbor') }}
                            </a>
                        </div>
                    </div>

                    <x-info-panel
                        :title="__('Nenašli jste odpověď?')"
                        :text="__('Napište nám a rádi pomůžeme — na e-maily odpovídáme obvykle do 2 pracovních dnů.')"
                        :button-text="__('Napsat e-mail')"
                        button-url="mailto:kontakt@poolbilliard.cz"
                        heading-level="h3"
                    />
                </aside>
            </div>
        </div>
    </section>

    <x-newsletter />
</x-layouts.app>
