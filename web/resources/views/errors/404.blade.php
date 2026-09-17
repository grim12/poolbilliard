{{--
    Laravel's conventional error view path (resources/views/errors/404.blade.php) — rendered for
    any 404 (unmatched route, or a route-model-bound record that isn't found/isn't public, e.g.
    HernaController's approved-only scope). No ui/ counterpart (not a designed page), but it
    reuses <x-layouts.app> + <x-no-results> like any other page, since a 404 doesn't mean the
    app/DB is broken (see errors/500.blade.php's docblock for why that one can't do the same).
--}}
<x-layouts.app
    :title="__('Stránka nenalezena').' — Poolbilliard'"
    :description="__('Hledaná stránka neexistuje nebo byla přesunuta.')"
>
    <section class="c-section">
        <div class="c-container text-center">
            <x-no-results
                icon="face-frown"
                :title="__('Stránka nenalezena (404)')"
                :text="__('Zkontrolujte prosím adresu v prohlížeči, nebo se vraťte na hlavní stránku.')"
            />
            <x-button :text="__('Zpět na hlavní stránku')" :url="route('home')" class="mt-6" />
        </div>
    </section>
</x-layouts.app>
