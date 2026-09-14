{{--
    <x-article-content :back-text :back-url :tag-text :tag-color :tag-position :title :date
                       :image :image-alt>
        {!! $article->body !!}
    </x-article-content>
    - Breadcrumb + header (category tag, title, date) + hero image for an article/notice detail
    page. Body copy goes in the default slot (trusted RichEditor-authored HTML — pass it
    through {!! !!} at the call site, same as ui/'s `| safe`).
    Mirrors ui/src/_includes/widgets/article-content.njk.
    - tagPosition: "above" (default) — tag on its own line above the title | "inline" — tag
      next to the date, below the title
--}}
@props([
    'backText' => 'Zpět na novinky',
    'backUrl' => '#',
    'tagText' => '',
    'tagColor' => 'primary',
    'tagPosition' => 'above',
    'title' => '',
    'date' => '',
    'image' => '',
    'imageAlt' => '',
])

<section {{ $attributes->merge(['class' => 'c-section c-section--article']) }}>
    <div class="c-container">
        <x-back-link :text="$backText" :url="$backUrl" />
    </div>

    <div class="c-container c-section__inner">
        <header class="c-section__header">
            @if ($tagText && $tagPosition === 'above')
                <x-tag :text="$tagText" :color="$tagColor" variant="plain" size="sm" class="c-section__tag" />
            @endif
            <h1 class="c-section__title">{{ $title }}</h1>
            @if ($tagPosition === 'inline' && ($tagText || $date))
                <div class="c-section__meta">
                    @if ($date)
                        <time class="c-section__date">{{ $date }}</time>
                    @endif
                    @if ($tagText)
                        <x-tag :text="$tagText" :color="$tagColor" variant="subtle" size="sm" />
                    @endif
                </div>
            @elseif ($date)
                <time class="c-section__date">{{ $date }}</time>
            @endif
        </header>

        @if ($image)
            <div class="c-section__media">
                <img src="{{ $image }}" alt="{{ $imageAlt }}" loading="lazy" />
            </div>
        @endif

        <div class="c-section__body">
            {{ $slot }}
        </div>
    </div>
</section>
