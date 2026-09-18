{{--
    Mirrors ui/src/_includes/layouts/header.njk. `headerDark` is passed in by
    <x-layouts.app> — see its docblock — and normally reflects
    GeneralSettings::$header_dark. `altLocaleUrl` is also from <x-layouts.app> (see its
    docblock) — the current page's exact counterpart in the other locale, used to wire the
    CZ/EN language switcher; falls back to that locale's homepage when there's no exact
    counterpart (e.g. an unmatched/aborted route).
--}}
@php
    $headerDark ??= false;
    $isEn = app()->getLocale() === 'en';
    $r = fn (string $name) => route(($isEn ? 'en.' : '').$name);
    $navItems = [
        [
            'text' => __('Novinky'),
            'url' => $r('novinky'),
            'children' => [
                ['text' => __('Články'), 'url' => $r('novinky')],
                ['text' => __('Zprávy výkonného výboru'), 'url' => $r('zpravodajstvi.vykonny-vybor')],
            ],
        ],
        ['text' => __('Kalendář'), 'url' => $r('kalendar')],
        [
            'text' => __('Kde hrát'),
            'url' => $r('kluby'),
            'children' => [
                ['text' => __('Kluby'), 'url' => $r('kluby')],
                ['text' => __('Herny'), 'url' => $r('herny')],
            ],
        ],
        ['text' => __('Soutěže'), 'url' => $r('souteze')],
        [
            'text' => __('Jak začít'),
            'url' => $r('jak-zacit'),
            'children' => [
                ['text' => __('Jsem začátečník'), 'url' => $r('jak-zacit').'#zacatecnik'],
                ['text' => __('Jsem rekreační hráč'), 'url' => $r('jak-zacit').'#rekreacni-hrac'],
                ['text' => __('Jsem rodič'), 'url' => $r('jak-zacit').'#rodic'],
                ['text' => __('Pravidla kulečníku'), 'url' => $r('pravidla')],
            ],
        ],
        ['text' => __('Svaz'), 'url' => $r('sportovni-svaz')],
    ];

    $csHref = $isEn ? ($altLocaleUrl ?? route('home')) : url()->current();
    $enHref = $isEn ? url()->current() : ($altLocaleUrl ?? route('en.home'));
@endphp

<header @class(['c-header', 't-dark' => $headerDark])>
    <div class="c-header__topbar">
        <div class="c-container c-header__topbar-inner">
            <ul class="c-header__topbar-links">
                <li><a href="{{ $r('faq') }}">FAQ</a></li>
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        ČMBS
                        <x-heroicon-m-arrow-top-right-on-square width="12" height="12" />
                    </a>
                </li>
            </ul>

            <ul class="c-header__topbar-socials">
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <x-brand-facebook width="18" height="18" />
                    </a>
                </li>
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <x-brand-instagram width="18" height="18" />
                    </a>
                </li>
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                        <x-brand-whatsapp width="18" height="18" />
                    </a>
                </li>
                <li>
                    <a href="#" class="c-header__topbar-tv" target="_blank" rel="noopener noreferrer">
                        <x-brand-youtube width="18" height="18" />
                        ČMBS TV
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="c-header__main">
        <div class="c-container c-header__main-inner">
            <a href="{{ $r('home') }}" class="c-header__brand" aria-label="{{ __('Český pool — domů') }}">
                <span class="c-header__brand-badge">
                    <img class="c-header__brand-logo" src="/uploads/cesky_pool.png" alt="Český Pool" />
                </span>
            </a>

            <nav class="c-header__nav" aria-label="{{ __('Hlavní navigace') }}">
                @foreach ($navItems as $item)
                    @if (! empty($item['children']))
                        <x-nav-dropdown :text="$item['text']" :url="$item['url']" :items="$item['children']" :is-active="$item['isActive'] ?? false" />
                    @else
                        <a @class(['c-header__nav-link', 'c-header__nav-link--is-active' => $item['isActive'] ?? false]) href="{{ $item['url'] }}">{{ $item['text'] }}</a>
                    @endif
                @endforeach
            </nav>

            <div class="c-header__actions">
                <x-button :text="__('Registrace na turnaje')" :url="$r('kalendar')" color="accent" size="sm" :has-arrow="false" />

                <div class="c-header__utility">
                    <div class="c-header__lang">
                        <button type="button" class="c-header__lang-btn" aria-haspopup="true" aria-expanded="false">
                            {{ $isEn ? 'EN' : 'CZ' }}
                            <x-heroicon-m-chevron-down class="c-header__lang-chevron" width="12" height="12" />
                        </button>
                        <div class="c-header__lang-dropdown">
                            <a href="{{ $csHref }}" @class(['c-header__lang-option', 'c-header__lang-option--is-active' => ! $isEn])>CZ — Čeština</a>
                            <a href="{{ $enHref }}" @class(['c-header__lang-option', 'c-header__lang-option--is-active' => $isEn])>EN — English</a>
                        </div>
                    </div>

                    <button type="button" class="c-header__search-toggle" data-search-toggle aria-expanded="false" aria-controls="header-search-panel" aria-label="{{ __('Hledat') }}">
                        <x-heroicon-m-magnifying-glass width="20" height="20" />
                    </button>
                </div>
            </div>

            <button type="button" class="c-header__toggle" data-header-toggle aria-expanded="false" aria-controls="header-mobile-panel" aria-label="{{ __('Otevřít menu') }}">
                <x-heroicon-m-bars-3 width="22" height="22" />
            </button>
        </div>

        <div class="c-header__search-panel" id="header-search-panel" data-search-panel>
            <div class="c-container c-header__search-panel-inner">
                <form class="c-header__search-form" role="search" action="#" method="get">
                    <button type="submit" class="c-header__search-submit" aria-label="{{ __('Hledat') }}">
                        <x-heroicon-m-magnifying-glass width="18" height="18" />
                    </button>
                    <input type="search" name="q" class="c-header__search-input" data-search-input placeholder="{{ __('Hledat kluby, hráče, novinky…') }}" />
                </form>
                <button type="button" class="c-header__search-close" data-search-close aria-label="{{ __('Zavřít vyhledávání') }}">
                    <x-heroicon-m-x-mark width="18" height="18" />
                </button>
            </div>
        </div>

        <div class="c-header__mobile-panel" id="header-mobile-panel" data-header-panel hidden>
            @foreach ($navItems as $item)
                <x-mobile-nav-item :text="$item['text']" :url="$item['url']" :children="$item['children'] ?? null" :is-active="$item['isActive'] ?? false" />
            @endforeach

            <div class="c-header__mobile-utility">
                <div class="c-header__lang-switch" role="group" aria-label="{{ __('Přepnout jazyk') }}">
                    <a href="{{ $csHref }}" @class(['c-header__lang-switch-option', 'c-header__lang-switch-option--is-active' => ! $isEn])>CZ</a>
                    <a href="{{ $enHref }}" @class(['c-header__lang-switch-option', 'c-header__lang-switch-option--is-active' => $isEn])>EN</a>
                </div>

                <button type="button" class="c-header__search-toggle" data-search-toggle aria-expanded="false" aria-controls="header-mobile-search-panel" aria-label="{{ __('Hledat') }}">
                    <x-heroicon-m-magnifying-glass width="20" height="20" />
                </button>
            </div>

            <div class="c-header__mobile-search" id="header-mobile-search-panel" data-search-panel>
                <div class="c-header__mobile-search-inner">
                    <form class="c-header__search-form" role="search" action="#" method="get">
                        <button type="submit" class="c-header__search-submit" aria-label="{{ __('Hledat') }}">
                            <x-heroicon-m-magnifying-glass width="18" height="18" />
                        </button>
                        <input type="search" name="q" class="c-header__search-input" data-search-input placeholder="{{ __('Hledat kluby, hráče, novinky…') }}" />
                    </form>
                    <button type="button" class="c-header__search-close" data-search-close aria-label="{{ __('Zavřít vyhledávání') }}">
                        <x-heroicon-m-x-mark width="18" height="18" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
