{{--
    Mirrors ui/src/_includes/layouts/footer.njk. `$r` mirrors layouts/header.blade.php's own
    locale-aware route helper (see its docblock) — kept local here too since footer is
    @include'd on its own, not passed anything by <x-layouts.app>.
--}}
@php
    $isEn = app()->getLocale() === 'en';
    $r = fn (string $name) => route(($isEn ? 'en.' : '').$name);
@endphp
<footer class="c-footer">
    <div class="c-container c-footer__top">
        <div class="c-footer__brand">
            <a href="{{ $r('home') }}" class="c-footer__logo" aria-label="{{ __('Český pool — domů') }}">
                <span class="c-footer__logo-badge">
                    <img class="c-footer__logo-img" src="/uploads/cesky_pool.png" alt="Český Pool" loading="lazy" />
                </span>
            </a>
            <p class="c-footer__tagline">{{ __('Centrální platforma Českého poolbilliardu, sportovní sekce, která je součástí Českomoravského billiardového svazu.') }}</p>

            <ul class="c-footer__socials">
                <li>
                    <a href="https://www.facebook.com/ceskypool" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <x-brand-facebook width="16" height="16" />
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/ceskypool/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <x-brand-instagram width="16" height="16" />
                    </a>
                </li>
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                        <x-brand-whatsapp width="16" height="16" />
                    </a>
                </li>
                <li>
                    <a href="https://www.youtube.com/cmbstv" target="_blank" rel="noopener noreferrer" aria-label="YouTube — ČMBS TV">
                        <x-brand-youtube width="16" height="16" />
                    </a>
                </li>
            </ul>
        </div>

        <nav class="c-footer__nav" aria-label="{{ __('Odkazy v patičce') }}">
            <div class="c-footer__nav-col">
                <p class="c-footer__nav-title">{{ __('Hraj') }}</p>
                <ul class="c-footer__nav-list">
                    <li><a href="{{ $r('kalendar') }}">{{ __('Kalendář') }}</a></li>
                    <li><a href="{{ $r('kluby') }}">{{ __('Kluby') }}</a></li>
                    <li><a href="{{ $r('herny') }}">{{ __('Herny') }}</a></li>
                    <li><a href="{{ $r('souteze') }}">{{ __('Soutěže') }}</a></li>
                </ul>
            </div>

            <div class="c-footer__nav-col">
                <p class="c-footer__nav-title">{{ __('Začni') }}</p>
                <ul class="c-footer__nav-list">
                    <li><a href="{{ $r('jak-zacit') }}">{{ __('Jak začít') }}</a></li>
                    <li><a href="{{ $r('pravidla') }}">{{ __('Pravidla kulečníku') }}</a></li>
                    <li><a href="{{ $r('kluby') }}">{{ __('Najdi si klub') }}</a></li>
                    <li><a href="{{ $r('herny') }}">{{ __('Najdi si hernu') }}</a></li>
                    <li><a href="{{ $r('faq') }}">{{ __('Časté dotazy') }}</a></li>
                </ul>
            </div>

            <div class="c-footer__nav-col">
                <p class="c-footer__nav-title">{{ __('Svaz') }}</p>
                <ul class="c-footer__nav-list">
                    <li><a href="{{ $r('sportovni-svaz') }}">{{ __('Sportovní svaz') }}</a></li>
                    <li><a href="{{ $r('novinky') }}">{{ __('Novinky') }}</a></li>
                    <li><a href="{{ $r('zpravodajstvi.vykonny-vybor') }}">{{ __('Výkonný výbor') }}</a></li>
                    <li><a href="{{ $r('partneri') }}">{{ __('Partneři') }}</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="c-footer__bottom">
        <div class="c-container c-footer__bottom-inner">
            <span class="c-footer__copyright">© {{ now()->year }} ČMBS — {{ __('Český poolbilliard') }}</span>
            <span class="c-footer__credit">{{ __('Vytvořeno s ❤ pro českou poolovou komunitu') }}</span>
        </div>
    </div>
</footer>
