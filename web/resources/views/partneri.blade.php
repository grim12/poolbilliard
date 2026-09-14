{{--
    Mirrors ui/src/partneri.njk. Site chrome (header/footer) now wired via <x-layouts.app>;
    pageHero() isn't ported yet, so the page starts straight at the partner grid — see
    skills/web-component-guide.md.
--}}
<x-layouts.app title="Partneři — Poolbilliard">
    <section class="c-section c-section--partner-directory">
        <div class="c-container">
            <div class="c-section__grid">
                @foreach ($partners as $partner)
                    <x-partner-card :name="$partner->name" :image="$partner->logo_url" :url="$partner->url" />
                @endforeach
            </div>
        </div>
    </section>

    <x-newsletter />
</x-layouts.app>
