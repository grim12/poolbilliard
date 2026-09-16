{{--
    <x-match-form :title :text :button-text />
    - "Find me a club/hall/coach" request form — static markup only (no real submit handler
      behind it yet, same as ui/'s prototype). Mirrors ui/src/_includes/macros/match-form.njk.
--}}
@props([
    'title' => '',
    'text' => '',
    'buttonText' => 'Odeslat poptávku',
])

<div {{ $attributes->merge(['class' => 'c-form']) }}>
    @if ($title)
        <h3 class="mb-2">{{ $title }}</h3>
    @endif
    @if ($text)
        <p class="c-form__text">{{ $text }}</p>
    @endif
    <form class="c-form__grid">
        <label class="c-form__field">
            <span class="c-form__label">Jméno</span>
            <input type="text" class="c-input" placeholder="Vaše jméno" />
        </label>
        <label class="c-form__field">
            <span class="c-form__label">E-mail</span>
            <input type="email" class="c-input" placeholder="vas@email.cz" />
        </label>
        <label class="c-form__field">
            <span class="c-form__label">Telefon (volitelné)</span>
            <input type="tel" class="c-input" placeholder="+420 123 456 789" />
        </label>
        <label class="c-form__field">
            <span class="c-form__label">Lokalita</span>
            <input type="text" class="c-input" placeholder="Město nebo region" />
        </label>
        <label class="c-form__field c-form__field--full">
            <span class="c-form__label">Co hledáte?</span>
            <textarea class="c-input c-form__textarea" rows="3" placeholder="Popište, co byste chtěli — trénink, klub, kroužek pro dítě, apod."></textarea>
        </label>
        <x-button :text="$buttonText" type="submit" class="sm:col-span-2 sm:w-fit" />
    </form>
</div>
