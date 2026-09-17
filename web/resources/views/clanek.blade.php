{{--
    Mirrors ui/src/article-detail.njk. Route: /novinky/{article:slug}.
--}}
@php
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $article->title,
        'datePublished' => $article->published_at?->toIso8601String(),
        'image' => $article->image_url,
        'url' => url()->current(),
        'publisher' => [
            '@type' => 'Organization',
            'name' => __('Český svaz poolbilliardu'),
        ],
    ];
@endphp
<x-layouts.app
    :title="$article->seo_title ?: $article->title"
    :description="$article->seo_description ?: \Illuminate\Support\Str::of($article->excerpt ?: $article->body)->stripTags()->squish()->limit(155)->toString()"
    og-type="article"
    :og-image="$article->seo_image_url ?: $article->image_url"
    :structured-data="$structuredData"
>
    <x-article-content
        class="bg-gradient-light"
        :back-url="\App\Support\Locale::route('novinky')"
        :tag-text="$article->category?->name"
        :tag-color="$article->category?->color ?? 'primary'"
        :title="$article->title"
        :date="$article->date_text"
        :image="$article->image_url"
    >
        {!! $article->body !!}
    </x-article-content>

    <x-gallery
        class="pt-none"
        :items="collect($article->gallery_urls)->map(fn ($url) => ['image' => $url, 'alt' => $article->title])->all()"
    />

    @if ($related->isNotEmpty())
        <x-related-articles class="bg-gray-100 border-top" :items="$related" />
    @endif

    <x-newsletter />
</x-layouts.app>
