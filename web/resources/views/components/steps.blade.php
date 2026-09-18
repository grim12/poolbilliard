{{--
    <x-steps :items />
    - Numbered how-to list: icon badge + title + text per row, in a light tinted row. Mirrors
      ui/src/_includes/macros/steps.njk.
    - items: array of ['icon' => heroicon name, 'title', 'text' => raw HTML allowed (e.g. an
      embedded <a> link — printed unescaped, same as ui/'s `| safe` filter; trusted
      admin-authored content, see JakZacitSectionForm's `steps` RichEditor). `title`
      conventionally includes its own "1. " prefix since the icon is illustrative, not a
      rendered step number.
--}}
@props([
    'items' => [],
])

<ol {{ $attributes->merge(['class' => 'c-steps']) }}>
    @foreach ($items as $item)
        <li class="c-steps__item">
            <span class="c-steps__icon" aria-hidden="true">
                <x-dynamic-component :component="'heroicon-m-'.$item['icon']" width="20" height="20" />
            </span>
            <div class="min-w-0">
                <p class="c-steps__title">{{ $item['title'] }}</p>
                @if (! empty($item['text']))
                    <p class="c-steps__text">{!! $item['text'] !!}</p>
                @endif
            </div>
        </li>
    @endforeach
</ol>
