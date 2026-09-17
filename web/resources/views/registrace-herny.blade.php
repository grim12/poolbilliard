{{--
    Mirrors ui/src/registrace-herny.njk. $regions/$sports: enum cases for the select/checkboxes.
    $days: the 7 weekday labels for the optional opening-hours rows. Submits to
    RegistraceHernyController::store(), which creates the Herna directly with
    HernaStatus::Pending (see that controller's docblock) — nothing here talks to Filament.
    A `submitted` session flash (set after a successful POST) swaps the form for a thank-you
    notice — the e-mail notifications noted in the controller are still an open follow-up.
--}}
<x-layouts.app
    :title="__('Registrace herny').' — Poolbilliard'"
    :description="__('Zaregistrujte svou kulečníkovou hernu zdarma do katalogu Českého poolbilliardu.')"
>
    <div class="bg-gradient-light">
        <section class="c-section pb-none">
            <div class="c-container">
                <x-back-link :text="__('Zpět na seznam heren')" :url="\App\Support\Locale::route('herny')" />
            </div>
        </section>

        <section class="c-section c-section--form pt-none pb-sm">
            <div class="c-container c-section__inner">
                <div class="mb-4">
                    <x-tag :text="__('Registrace herny')" color="primary" variant="subtle" size="sm" />
                </div>
                <h1 class="mb-3">{{ __('Přidejte svou hernu do katalogu') }}</h1>
                <p class="p--lg mb-8">{{ __('Provozujete kulečníkovou hernu? Zaregistrujte ji zdarma do adresáře Český Poolbilliard. Po schválení se objeví v seznamu heren a na interaktivní mapě, kde si vás najdou hráči z vašeho okolí.') }}</p>

                @if (session('submitted'))
                    <x-alert
                        icon="check-circle"
                        color="primary"
                        :title="__('Děkujeme za registraci')"
                        :text="__('Vaši hernu jsme přijali ke schválení. Ozveme se, jakmile ji ověříme a zařadíme do katalogu.')"
                    />
                @else
                    <x-alert
                        icon="information-circle"
                        :title="__('Jak to funguje')"
                        :text="__('Vyplňte pole níže — čím kompletnější údaje, tím rychleji hernu schválíme. Povinné údaje jsou označené hvězdičkou. Poloha herny se používá pro zobrazení na mapě; pokud GPS souřadnice neznáte, vyplňte je nulou a doplníme je při schvalování.')"
                    />
                @endif
            </div>
        </section>
    </div>

    @unless (session('submitted'))
        <section class="c-section c-section--form pt-none">
            <div class="c-container c-section__inner">
                <form class="c-form" method="POST" action="{{ \App\Support\Locale::route('registrace-herny.store') }}">
                    @csrf

                    <div class="hidden" aria-hidden="true">
                        <label>
                            {{ __('Nechte prázdné') }}
                            <input type="text" name="company" tabindex="-1" autocomplete="off" />
                        </label>
                    </div>

                    <div class="c-form__group">
                        <h3 class="c-form__group-title">{{ __('Základní údaje') }}</h3>
                        <div class="c-form__grid">
                            <label class="c-form__field c-form__field--full">
                                <span class="c-form__label">{{ __('Název herny *') }}</span>
                                <input type="text" name="name" class="c-input" placeholder="{{ __('Např. Harlequin Pool Club') }}" value="{{ old('name') }}" required />
                                @error('name') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="c-form__field c-form__field--full">
                                <span class="c-form__label">{{ __('Popis herny *') }}</span>
                                <textarea name="description" class="c-input c-form__textarea" rows="4" placeholder="{{ __('Krátký popis herny, vybavení, atmosféry, počet stolů...') }}" required>{{ old('description') }}</textarea>
                                @error('description') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </label>
                        </div>
                    </div>

                    <div class="c-form__group">
                        <h3 class="c-form__group-title">{{ __('Adresa a poloha') }}</h3>
                        <div class="c-form__grid">
                            <label class="c-form__field c-form__field--full">
                                <span class="c-form__label">{{ __('Ulice a číslo popisné *') }}</span>
                                <input type="text" name="address" class="c-input" placeholder="Františka Křížka 11" value="{{ old('address') }}" required />
                                @error('address') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="c-form__field">
                                <span class="c-form__label">{{ __('Město *') }}</span>
                                <input type="text" name="city" class="c-input" placeholder="Praha 7" value="{{ old('city') }}" required />
                                @error('city') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="c-form__field c-form__field--full">
                                <span class="c-form__label">{{ __('Kraj *') }}</span>
                                <select name="region" class="c-select" required>
                                    <option value="" selected disabled>{{ __('Vyberte kraj...') }}</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->value }}" @selected(old('region') === $region->value)>{{ $region->getLabel() }}</option>
                                    @endforeach
                                </select>
                                @error('region') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="c-form__field">
                                <span class="c-form__label">{{ __('Zeměpisná šířka (lat)') }}</span>
                                <input type="text" name="lat" class="c-input" placeholder="50.0975" value="{{ old('lat') }}" />
                            </label>
                            <label class="c-form__field">
                                <span class="c-form__label">{{ __('Zeměpisná délka (lng)') }}</span>
                                <input type="text" name="lng" class="c-input" placeholder="14.4344" value="{{ old('lng') }}" />
                            </label>
                        </div>
                    </div>

                    <div class="c-form__group">
                        <h3 class="c-form__group-title">{{ __('Nabízené sporty *') }}</h3>
                        <div class="c-check-pills">
                            @foreach ($sports as $sport)
                                <label class="c-check-pill">
                                    <input type="checkbox" name="sports[]" value="{{ $sport->value }}" class="c-check-pill__input" @checked(in_array($sport->value, old('sports', []), true)) />
                                    <span class="c-check-pill__box"><x-heroicon-m-check width="12" height="12" /></span>
                                    {{ $sport->getLabel() }}
                                </label>
                            @endforeach
                        </div>
                        @error('sports') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="c-form__group">
                        <h3 class="c-form__group-title">{{ __('Otevírací doba (nepovinné)') }}</h3>
                        <div class="c-form__grid">
                            @foreach ($days as $day)
                                <label class="c-form__field">
                                    <span class="c-form__label">{{ $day }}</span>
                                    <input type="text" name="hours[{{ $day }}]" class="c-input" placeholder="{{ __('např. 14:00–24:00 nebo Zavřeno') }}" value="{{ old("hours.$day") }}" />
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="c-form__group">
                        <h3 class="c-form__group-title">{{ __('Kontakt (nepovinné)') }}</h3>
                        <div class="c-form__grid">
                            <label class="c-form__field">
                                <span class="c-form__label">{{ __('Telefon') }}</span>
                                <input type="tel" name="phone" class="c-input" placeholder="+420 ..." value="{{ old('phone') }}" />
                            </label>
                            <label class="c-form__field">
                                <span class="c-form__label">{{ __('E-mail') }}</span>
                                <input type="email" name="email" class="c-input" placeholder="info@herna.cz" value="{{ old('email') }}" />
                                @error('email') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="c-form__field c-form__field--full">
                                <span class="c-form__label">{{ __('Web') }}</span>
                                <input type="url" name="website" class="c-input" placeholder="https://" value="{{ old('website') }}" />
                                @error('website') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <x-button :text="__('Odeslat ke schválení')" type="submit" leading-icon="paper-airplane" :has-arrow="false" />
                    </div>
                </form>
            </div>
        </section>
    @endunless

    <x-newsletter />
</x-layouts.app>
