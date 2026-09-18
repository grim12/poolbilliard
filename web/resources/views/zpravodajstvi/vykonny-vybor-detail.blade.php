{{--
    Mirrors ui/src/zpravodajstvi/vykonny-vybor-detail.njk — reuses <x-article-content>
    (tagPosition="inline", accent color), same as ui/'s own reuse of articleContent() there.
--}}
@php
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $notice->title,
        'datePublished' => $notice->published_at?->toIso8601String(),
        'url' => url()->current(),
        'publisher' => [
            '@type' => 'Organization',
            'name' => __('Český svaz poolbilliardu'),
        ],
    ];
@endphp
<x-layouts.app
    :title="$notice->seo_title ?: $notice->title"
    :description="$notice->seo_description ?: \Illuminate\Support\Str::of($notice->excerpt ?: $notice->body)->stripTags()->squish()->limit(155)->toString()"
    og-type="article"
    :og-image="$notice->seo_image_url"
    :structured-data="$structuredData"
>
    <x-article-content
        class="bg-gradient-light"
        :back-text="__('Zpět na zprávy výboru')"
        :back-url="\App\Support\Locale::route('zpravodajstvi.vykonny-vybor')"
        :tag-text="$notice->is_important ? __('Důležité') : ''"
        tag-color="accent"
        tag-position="inline"
        :title="$notice->title"
        :date="$notice->date_text"
    >
        {!! $notice->body !!}
    </x-article-content>

    <x-newsletter />
</x-layouts.app>
