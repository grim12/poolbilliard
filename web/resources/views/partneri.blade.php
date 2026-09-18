{{--
    Mirrors ui/src/partneri.njk. Site chrome (header/footer) now wired via <x-layouts.app>;
    pageHero() isn't ported yet, so the page starts straight at the partner grid — see
    skills/web-component-guide.md.
--}}
<x-layouts.app
    :title="\App\Support\Locale::field($settings, 'seo_title') ?: __('Partneři')"
    :description="\App\Support\Locale::field($settings, 'seo_description') ?: __('Partneři a sponzoři Českého svazu poolbilliardu, kteří podporují rozvoj poolbilliardu v Česku.')"
    :og-image="\App\Support\Seo::imageUrl($settings->seo_image)"
>
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
