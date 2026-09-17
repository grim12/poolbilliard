{{--
    Mirrors ui/src/zpravodajstvi/vykonny-vybor-detail.njk — reuses <x-article-content>
    (tagPosition="inline", accent color), same as ui/'s own reuse of articleContent() there.
--}}
<x-layouts.app
    :title="$notice->title.' — Poolbilliard'"
    :description="\Illuminate\Support\Str::of($notice->excerpt ?: $notice->body)->stripTags()->squish()->limit(155)->toString()"
    og-type="article"
>
    <x-article-content
        class="bg-gradient-light"
        back-text="Zpět na zprávy výboru"
        :back-url="route('zpravodajstvi.vykonny-vybor')"
        :tag-text="$notice->is_important ? 'Důležité' : ''"
        tag-color="accent"
        tag-position="inline"
        :title="$notice->title"
        :date="$notice->date_text"
    >
        {!! $notice->body !!}
    </x-article-content>

    <x-newsletter />
</x-layouts.app>
