{{--
    Mirrors ui/src/_includes/layouts/header.njk. `headerDark` is passed in by
    <x-layouts.app> — see its docblock — and normally reflects
    GeneralSettings::$header_dark.
--}}
@php
    $headerDark ??= false;
    $navItems = [
        [
            'text' => 'Novinky',
            'url' => '/novinky/',
            'children' => [
                ['text' => 'Články', 'url' => '/novinky/'],
                ['text' => 'Zprávy výkonného výboru', 'url' => '/zpravodajstvi/vykonny-vybor'],
            ],
        ],
        ['text' => 'Kalendář', 'url' => '/kalendar/'],
        [
            'text' => 'Kde hrát',
            'url' => '/kluby/',
            'children' => [
                ['text' => 'Kluby', 'url' => '/kluby/'],
                ['text' => 'Herny', 'url' => '/herny/'],
            ],
        ],
        ['text' => 'Soutěže', 'url' => '/souteze/'],
        [
            'text' => 'Jak začít',
            'url' => '/jak-zacit/',
            'children' => [
                ['text' => 'Jsem začátečník', 'url' => '/jak-zacit/#zacatecnik'],
                ['text' => 'Jsem rekreační hráč', 'url' => '/jak-zacit/#rekreacni-hrac'],
                ['text' => 'Jsem rodič', 'url' => '/jak-zacit/#rodic'],
                ['text' => 'Pravidla kulečníku', 'url' => '/pravidla/'],
            ],
        ],
        ['text' => 'Svaz', 'url' => '/sportovni-svaz/'],
    ];
@endphp

<header @class(['c-header', 't-dark' => $headerDark])>
    <div class="c-header__topbar">
        <div class="c-container c-header__topbar-inner">
            <ul class="c-header__topbar-links">
                <li><a href="/faq/">FAQ</a></li>
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
            <a href="/" class="c-header__brand" aria-label="Český pool — domů">
                <span class="c-header__brand-badge">
                    <img class="c-header__brand-logo" src="/uploads/cesky_pool.png" alt="Český Pool" />
                </span>
            </a>

            <nav class="c-header__nav" aria-label="Hlavní navigace">
                @foreach ($navItems as $item)
                    @if (! empty($item['children']))
                        <x-nav-dropdown :text="$item['text']" :url="$item['url']" :items="$item['children']" :is-active="$item['isActive'] ?? false" />
                    @else
                        <a @class(['c-header__nav-link', 'c-header__nav-link--is-active' => $item['isActive'] ?? false]) href="{{ $item['url'] }}">{{ $item['text'] }}</a>
                    @endif
                @endforeach
            </nav>

            <div class="c-header__actions">
                <x-button text="Registrace na turnaje" url="/kalendar/" color="accent" size="sm" :has-arrow="false" />

                <div class="c-header__utility">
                    <div class="c-header__lang">
                        <button type="button" class="c-header__lang-btn" aria-haspopup="true" aria-expanded="false">
                            CZ
                            <x-heroicon-m-chevron-down class="c-header__lang-chevron" width="12" height="12" />
                        </button>
                        <div class="c-header__lang-dropdown">
                            <a href="#" class="c-header__lang-option c-header__lang-option--is-active">CZ — Čeština</a>
                            <a href="#" class="c-header__lang-option">EN — English</a>
                        </div>
                    </div>

                    <button type="button" class="c-header__search-toggle" data-search-toggle aria-expanded="false" aria-controls="header-search-panel" aria-label="Hledat">
                        <x-heroicon-m-magnifying-glass width="20" height="20" />
                    </button>
                </div>
            </div>

            <button type="button" class="c-header__toggle" data-header-toggle aria-expanded="false" aria-controls="header-mobile-panel" aria-label="Otevřít menu">
                <x-heroicon-m-bars-3 width="22" height="22" />
            </button>
        </div>

        <div class="c-header__search-panel" id="header-search-panel" data-search-panel>
            <div class="c-container c-header__search-panel-inner">
                <form class="c-header__search-form" role="search" action="#" method="get">
                    <button type="submit" class="c-header__search-submit" aria-label="Hledat">
                        <x-heroicon-m-magnifying-glass width="18" height="18" />
                    </button>
                    <input type="search" name="q" class="c-header__search-input" data-search-input placeholder="Hledat kluby, hráče, novinky…" />
                </form>
                <button type="button" class="c-header__search-close" data-search-close aria-label="Zavřít vyhledávání">
                    <x-heroicon-m-x-mark width="18" height="18" />
                </button>
            </div>
        </div>

        <div class="c-header__mobile-panel" id="header-mobile-panel" data-header-panel hidden>
            @foreach ($navItems as $item)
                <x-mobile-nav-item :text="$item['text']" :url="$item['url']" :children="$item['children'] ?? null" :is-active="$item['isActive'] ?? false" />
            @endforeach

            <div class="c-header__mobile-utility">
                <div class="c-header__lang-switch" role="group" aria-label="Přepnout jazyk">
                    <button type="button" class="c-header__lang-switch-option c-header__lang-switch-option--is-active">CZ</button>
                    <button type="button" class="c-header__lang-switch-option">EN</button>
                </div>

                <button type="button" class="c-header__search-toggle" data-search-toggle aria-expanded="false" aria-controls="header-mobile-search-panel" aria-label="Hledat">
                    <x-heroicon-m-magnifying-glass width="20" height="20" />
                </button>
            </div>

            <div class="c-header__mobile-search" id="header-mobile-search-panel" data-search-panel>
                <div class="c-header__mobile-search-inner">
                    <form class="c-header__search-form" role="search" action="#" method="get">
                        <button type="submit" class="c-header__search-submit" aria-label="Hledat">
                            <x-heroicon-m-magnifying-glass width="18" height="18" />
                        </button>
                        <input type="search" name="q" class="c-header__search-input" data-search-input placeholder="Hledat kluby, hráče, novinky…" />
                    </form>
                    <button type="button" class="c-header__search-close" data-search-close aria-label="Zavřít vyhledávání">
                        <x-heroicon-m-x-mark width="18" height="18" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
