# Web (Laravel + Filament) Component & Workflow Guide

> Tento návod slouží pro vývojáře a AI asistenty pracující na backendové části projektu (`web/`).
> Je průběžně aktualizovaný v souběhu s prací — doplňujte ho, jakmile se ustálí nová konvence
> (datový model, tvar Filament resource, vzor Blade komponenty apod.).
> Obecné Laravel/PHP/testing konvence spravuje [Laravel Boost](https://github.com/laravel/boost)
> v `web/AGENTS.md` / `web/CLAUDE.md` (needitovat ručně, viz níže) — tenhle soubor pokrývá jen to,
> co je specifické pro tenhle projekt.

---

## 1. Technologický stack & Architektura

* **Framework:** [Laravel 13](https://laravel.com/)
* **Admin panel:** [Filament v5](https://filamentphp.com/)
* **PHP:** 8.5
* **Databáze:** SQLite lokálně (rychlý start), MySQL na produkci (hosting spravuje DB přes phpMyAdmin) — až bude potřeba testovat proti reálnému enginu, přepneme `.env` na MySQL i lokálně.
* **Frontend build:** Vite (`npm run dev` / `npm run build`), Tailwind CSS 4 — styly a JS jsou **zrcadlené** z `ui/`, ne nezávisle psané (viz sekce 3).
* **AI asistence:** Laravel Boost (`composer require laravel/boost --dev` + `php artisan boost:install --guidelines`) — generuje `AGENTS.md`/`CLAUDE.md` s konvencemi podle nainstalovaných balíčků. Tyto dva soubory jsou nástrojem spravované, needitujte je ručně — spusťte znovu `php artisan boost:install` po přidání nového balíčku, ať se guidelines aktualizují.
* **Globální nastavení (admin-editovatelné, ne v `config/`):** [`spatie/laravel-settings`](https://github.com/spatie/laravel-settings) + [`filament/spatie-laravel-settings-plugin`](https://github.com/filamentphp/spatie-laravel-settings-plugin). Settings třída v `app/Settings/<Name>Settings.php` (auto-discovered), počáteční hodnota přes `php artisan make:settings-migration <Name>` (soubor v `database/settings/`), admin stránka ručně napsaná jako `app/Filament/Pages/Manage<Name>Settings.php` extends `Filament\Pages\SettingsPage` (generátor `make:filament-settings-page` padá na name-collision, když se stránka i settings třída jmenují stejně — pojmenuj stránku `Manage<Name>Settings`, ne `<Name>Settings`). Referenční příklad: `GeneralSettings` (`tournament_soon_threshold_days`), viz sekce 4.

### Adresářová struktura (`web/`, relevantní pro naši práci)

```text
web/
├── app/
│   ├── Filament/           # Filament Resources (admin CRUD) — jeden adresář na entitu
│   ├── Http/Controllers/   # Jeden controller na veřejnou stránku/entitu (ne closures v routes/web.php)
│   ├── Models/             # Eloquent modely
│   └── Providers/Filament/AdminPanelProvider.php
├── resources/
│   ├── views/
│   │   ├── components/     # Blade komponenty — protějšek ui/src/_includes/macros
│   │   └── <page>.blade.php # Veřejné stránky — protějšek ui/src/*.njk
│   ├── css/                # Zrcadlené Tailwind styly z ui/src/styles (+ @theme v app.css)
│   └── js/                 # Zrcadlený JS z ui/src/js
├── database/
│   ├── migrations/
│   ├── factories/, seeders/
│   └── seeders/assets/<entita>/ # Committed zdrojové obrázky pro seed (viz sekce 4)
├── routes/web.php
├── AGENTS.md, CLAUDE.md    # Laravel Boost — needitovat ručně
└── boost.json
```

**Site chrome (header/hlavní menu/footer/newsletter) je od teď hotové** — `<x-layouts.app>`
(viz sekce 3) obaluje každou veřejnou stránku. `pageHero()`/`linkTiles()` a další jednotlivé
widgety ještě ne, doplňují se postupně, jak na ně dojde řada.

**Referenční příklady end-to-end features** (DB → Model → Filament Resource → Blade → route):
* `Partner` — jeden obrázek na entitu (`FileUpload` + `logo_url` accessor), `sort_order`
  s drag&drop řazením. `app/Models/Partner.php`, `database/seeders/PartnerSeeder.php`,
  `app/Filament/Resources/Partners/`, `resources/views/partneri.blade.php` +
  `resources/views/components/partner-card.blade.php`, route `/partneri`.
* `FaqItem` — rich-text pole (`RichEditor`), akordeon s JS chováním portovaným z `main.js`.
  `app/Models/FaqItem.php`, `database/seeders/FaqItemSeeder.php`,
  `app/Filament/Resources/FaqItems/`, `resources/views/faq.blade.php` +
  `resources/views/components/faq.blade.php`, route `/faq`.
  **Umístění (které stránky/sekce položku zobrazí) řeší many-to-many `FaqGroup`**
  (`name`, `slug`, `sort_order`) — jedna centrální správa FAQ položek, obsah se filtruje podle
  skupiny (`FaqItem::whereHas('groups', fn ($q) => $q->where('slug', 'obecne'))`), ne
  duplikovaný/spravovaný zvlášť pro každou stránku. `belongsToMany`, ne `belongsTo`, protože
  jedna otázka může dávat smysl na víc místech zároveň (viz bod 4).
* `Tournament` — `badge` jako `boolean` (Filament `Toggle`/`IconColumn`), a `soon`/`dateText`
  jako **computed accessory** místo ručních polí (`start_date`/`end_date` + globální
  `GeneralSettings::$tournament_soon_threshold_days`, viz body 1 a 4). Kategorie (dřív volné
  `tag_text`/`tag_color` na každém turnaji) je vlastní model `TournamentCategory` (name, color,
  `sort_order`) — **taxonomie/číselník jako samostatná tabulka + `belongsTo`, ne volný text
  opakovaný na každém záznamu** (viz bod 4). `app/Models/{Tournament,TournamentCategory}.php`,
  `database/seeders/{TournamentCategorySeeder,TournamentSeeder}.php`,
  `app/Filament/Resources/{Tournaments,TournamentCategories}/`, `resources/views/turnaje.blade.php` +
  `resources/views/components/{tournament-card,tournaments}.blade.php`, route `/turnaje`.
  **Poznámka:** `ui/` nemá pro tenhle grid samostatnou stránku (jen homepage sekce + plný
  Kalendář s JS filtry) — `/turnaje` je dočasná ukázková route, ne 1:1 port existující `ui/`
  stránky.
* Atomické komponenty `tag` a `button` (`resources/views/components/{tag,button}.blade.php`)
  jsou portované jako samostatné, znovupoužitelné Blade komponenty (ne duplikované do každého
  widgetu) — viz jejich použití v `info-panel.blade.php` i `tournament-card.blade.php`.
* `Club` a `Herna` — první entity s reálným per-záznamovým routováním (`ui/`'s `klub.njk`/
  `herna.njk` jsou napevno jedna ukázková stránka bez dynamického routování), řeší `HasSlug`
  trait (`app/Models/Concerns/HasSlug.php`, auto-slug z `name` při vytvoření). `Club` má
  `members` (hasMany `ClubMember`, Filament `Repeater` s `->relationship()`), `ambassador_*`
  pole a nábor (`recruitment_open` boolean + `recruitment_text` nepovinný rich text s globálním
  fallbackem, viz bod 4). `Herna` má `sports`/`hours`/`gallery` jako obyčejné JSON sloupce (ne
  tabulky — viz bod 4) a `status` (`HernaStatus` enum) pro budoucí schvalovací workflow
  veřejné registrace (formulář samotný ještě není portovaný). `Region`/`Sport`/`HernaStatus`
  jsou PHP backed enumy v `app/Enums/`, ne DB taxonomie — viz bod 4. Zatím bez site chrome i bez
  veřejné Blade stránky (jen model + Filament resources) — na rozdíl od předchozích entit jsme
  se tentokrát zastavili po admin straně, veřejná `/kluby`, `/klub/{slug}`, `/herny`,
  `/herna/{slug}` (vč. Leaflet mapy, self-hosted, žádný API klíč, generický `[data-club-map]`
  init v `ui/src/js/main.js`) jsou samostatný další krok.
* **Podmíněné třídy v Blade:** pro `class="a @if(...) b @endif"` použij radši `@class(['a', 'b' => $podminka])` direktivu (nedělá nadbytečné mezery v atributu) — viz `tournament-card.blade.php`.
* **Brand ikonky (Simple Icons) jsou portované** (viz sekce 3, "Site chrome") — dostupné jako `<x-brand-facebook>` atd., ne přes shortcode syntaxi.
* `Article` + `ArticleCategory` a `Notice` — další dvojice "má kategorii" vs. "nemá kategorii,
  jen boolean", stejný rozhodovací pár jako `TournamentCategory`/`badge` bool. `ArticleCategory`
  (name, color, sort_order, `belongsTo` z `Article` — barva je 1:1 vlastnost kategorie, ne volně
  nastavitelná per článek, viz bod 4) vs. `Notice::$is_important` (prostý boolean, žádná
  kategorie — přesně podle zadání). Oba mají `HasSlug`, `gallery`/`image` (`Article`) po vzoru
  `Herna`/`Partner`, a `published_at` (skutečné datum) + computed `dateText()` accessor (stejný
  vzor jako `Tournament::dateText()`) místo ručně psaného textového data. `app/Models/{Article,
  ArticleCategory,Notice}.php`, `database/seeders/{ArticleCategorySeeder,ArticleSeeder,
  NoticeSeeder}.php`, `app/Filament/Resources/{Articles,ArticleCategories,Notices}/`.
  **`Article` má teď i veřejné stránky** (`/novinky` výpis + `/novinky/{article:slug}` detail,
  `ArticleController`, `resources/views/{novinky,clanek}.blade.php` + komponenty
  `article-card`/`article-content`/`gallery`/`related-articles`/`news-header`/`pagination`) —
  viz sekce 3 "Novinky/Článek". **`Notice` má teď taky veřejné stránky**
  (`/zpravodajstvi/vykonny-vybor` výpis + `/zpravodajstvi/vykonny-vybor/{notice:slug}` detail,
  `NoticeController`, `resources/views/zpravodajstvi/{vykonny-vybor,vykonny-vybor-detail}.blade.php`
  + `components/notice-card.blade.php`) — viz sekce 3 "Zprávy výboru".

---

## 2. Lokální vývoj

```bash
cd web
composer install
npm install
php artisan serve      # http://localhost:8000 (admin: /admin)
npm run dev             # Vite dev server, nebo `composer run dev` spustí server+vite+queue najednou
```

Admin uživatel: `php artisan make:filament-user`.

DB je zatím SQLite (`database/database.sqlite`) — žádná závislost na běžícím MySQL serveru. Přepnutí na MySQL (až bude potřeba): upravit `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` v `.env` a spustit `php artisan migrate:fresh`.

> ⚠️ **PAST: `DatabaseSeeder`/libovolný seeder nesmí mít `use WithoutModelEvents;`, pokud se v projektu spoléháme na model eventy (např. `HasSlug`'s `creating`).** Laravel to do `DatabaseSeeder` scaffoldu dává defaultně (kvůli rychlosti u `User::factory(10)->create()`), ale ten trait **potichu vypne všechny model eventy pro celý běh seederu** — `slug` se pak nikdy nedopočítá a insert spadne na `NOT NULL constraint` bez zjevné souvislosti s eventy. Trait jsme z `DatabaseSeeder` odstranili — nepřidávej ho zpátky, pokud si nejsi jistý, že žádný aktivní model nespoléhá na `creating`/`saving`/atd.

---

## 3. Workflow: přenos z `ui/` do `web/`

Viz i pravidlo v hlavním `README.md`. `ui/` je zdroj pravdy pro vzhled — v `web/` se nikdy nedělá vizuální změna jako první.

### Postup portování jedné komponenty (makro → Blade komponenta)

1. Otevři hotové/odladěné makro v `ui/src/_includes/macros/<name>.njk` a jeho CSS v `ui/src/styles/02_components/<name>.css` (nebo `section/<name>.css` u `.c-section--*` variant).
2. Vytvoř Blade protějšek v `web/resources/views/components/<name>.blade.php`. Parametry makra (`{% macro foo(a, b="x") %}`) → `@props(['a', 'b' => 'x'])`.
3. **BEM třídy (`.c-*`, `.u-*`, `.t-*`) se přenášejí beze změny** — stejný název třídy v Blade i Nunjucks, ať CSS soubor (bod 4) sedí na obě strany beze změny.
4. Zkopíruj příslušný CSS soubor 1:1 do `web/resources/css/<stejná cesta jako v ui/>` (žádné přepisování na jinou metodiku — pořád Tailwind 4 + `@apply` + BEM, viz `skills/ui-component-guide.md` Pravidlo 3).
5. Přidej `@import './<stejná cesta>';` do `web/resources/css/app.css` (soubory, případně `02_components/section.css`, mají u sebe komentář, že seznam importů odráží jen to, co je na `web/` straně už portované — udržuj ho v souladu s tím, co skutečně existuje, ne s celým seznamem z `ui/`).
6. Pokud makro obsahuje `{% icon %}` shortcode (Heroicons), použij Blade ekvivalent — `blade-ui-kit/blade-heroicons` je už závislost Filamentu. **Projekt preferuje "mini" (`m-`) geometrii i pro větší ikony** (stejná konvence jako `ui/`, viz `skills/ui-component-guide.md` Pravidlo 7): `<x-heroicon-m-chevron-down width="20" height="20" class="..." />`. Když je název ikony proměnná (např. `button()`'s `icon`/`leadingIcon` parametr), použij `<x-dynamic-component :component="'heroicon-m-'.$icon" :width="$iconSize" :height="$iconSize" />` — viz `components/button.blade.php`. Pro `{% brandIcon %}` (Simple Icons) použij `<x-brand-facebook>` atd. — viz "Brand ikonky" níže.
7. Ověř vizuálně vedle sebe (`ui/` dev server vs. `web/` dev server) — musí sedět 1:1.

**Design tokeny** (barvy, radius, fonty, stíny) jsou v `web/resources/css/app.css` uvnitř `@theme { ... }` — je to ruční zrcadlo `@theme` bloku z `ui/src/styles/style.css`. Změníš-li token v `ui/`, proveď stejnou změnu i tady (obě místa výslovně na sebe odkazují komentářem).

**`extraClass` parametr maker se do Blade nepřenáší jako samostatný prop** — Blade má nativní mechanismus přesně pro tohle (`$attributes`), použij ho: komponenta v základu vrací `{{ $attributes->merge(['class' => $classes]) }}` na root elementu, volající pak přidá extra třídy prostě jako `class="..."` atribut na tag komponenty (`<x-tag ... class="mb-2" />`), stejně jako `id`, `data-*` apod. Viz `components/tag.blade.php`, `components/button.blade.php`.

**Rich-text pole** (makro používalo `| safe` filtr na hodnotu z dat, např. FAQ odpověď): v DB obyčejný `text` sloupec s uloženým HTML, ve Filament formuláři `RichEditor::make(...)` (ne `Textarea`/`TextInput` — administrátor needituje HTML ručně), v Blade vypsat neescapovaně přes `{!! $value !!}` (Blade ekvivalent `| safe`). Viz `FaqItemForm` + `components/faq.blade.php`.

**JS chování widgetu**: pokud makro/widget má v `main.js` vlastní, samostatný blok chování (např. FAQ akordeon `[data-faq-toggle]`), zkopíruj jen ten blok do `web/resources/js/app.js` (ne celý `main.js` najednou — pořád tam zbývá dost widget-specifických bloků, co nemají Blade protějšek, např. jump-nav, document-tabs, Leaflet mapa). Odkazuj v komentáři na zdrojový blok v `ui/src/js/main.js`.

**Brand ikonky (Simple Icons)** — žádný oficiální Composer/Blade balíček neexistuje, takže těch pár, co potřebujeme (facebook/instagram/whatsapp/youtube v headeru a patičce), je committnutých natvrdo jako obyčejná SVG v `web/resources/svg/brand/` (zrcadlí `ui/.eleventy.js`'s `brandIcon()` shortcode: `<title>` pryč, `fill="currentColor"` napečené do souboru — zdroj `ui/node_modules/simple-icons/icons/<name>.svg`). Použití: `<x-brand-facebook width="18" height="18" />`. **Nová sada musí být zaregistrovaná v `config/blade-icons.php`'s `sets`, ne v service provideru přes `Factory::add()` v `boot()`** — `Factory`/`IconsManifest` jsou singletony, které si seznam ikon k Blade komponentám zapamatují (memoizují) při prvním resolvnutí view factory, což se stane už během `register()` fáze jiných providerů, dávno před tím, než doběhne `boot()` naší vlastní `AppServiceProvider` (ověřeno — přidání setu v `boot()` tiše nefungovalo, `config/blade-icons.php` ano). Přidání další ikony: zkopírovat SVG (title pryč, `fill="currentColor"` na `<svg>`) do `resources/svg/brand/`, hotovo — soubor se objeví automaticky, žádná další registrace není potřeba.

### Site chrome (layout, header, hlavní menu, footer, newsletter)

- `<x-layouts.app title="..." >...</x-layouts.app>` (`resources/views/components/layouts/app.blade.php`) je Blade **layout komponenta** (ne `@extends`/`@section`) obalující každou veřejnou stránku — zrcadlí `ui/src/_includes/layouts/base.njk` (`<html>`/`<head>`/`@vite`) + `<div class="c-page-wrapper">` + header/footer, které si `ui/` opakuje v každé stránce zvlášť. Zatím bez `hasGallery`/`hasMap` vendor asset pipeline (GLightbox/Leaflet nejsou portované) — propy existují jen pro budoucí parity, negatují nic.
- `layouts.header`/`layouts.footer` (`resources/views/layouts/{header,footer}.blade.php`) jsou obyčejné **view partiály** (přes `@include`, ne komponenty s propy) — zrcadlí `ui/src/_includes/layouts/{header,footer}.njk` 1:1, včetně `navItems` pole natvrdo v `@php` bloku (stejně jako `ui/`'s `{% set navItems = [...] %}`).
- `ui/`'s lokální Nunjucks makra uvnitř `header.njk` (`navDropdown`, `mobileNavItem`) nemají v Blade ekvivalent "makro v rámci jednoho souboru" — staly se z nich samostatné komponenty `components/nav-dropdown.blade.php` a `components/mobile-nav-item.blade.php`. `mobile-nav-item` už obsahuje starší UI opravu (text je vždy skutečný odkaz, jen šipka je toggle) — needituj to zpátky na "celý řádek = toggle".
- Statické brand assety bez DB záznamu (logo v headeru/patičce) patří do `public/uploads/` přímo (`web/public/uploads/cesky_pool.png`) — **ne** přes `Storage::disk('public')` seeder-assets vzor (ten je pro DB-vázaný obsah, viz `PartnerSeeder`). Cesta `/uploads/...` v Blade je stejná jako v `ui/`.
- `newsletter()` → `<x-newsletter />` (`components/newsletter.blade.php`), stejné propy jako makro.
- Ověření: `tests/Feature/PublicPagesTest.php` kontroluje, že veřejné stránky vrací 200 **a** obsahují chrome markery (`c-header__nav`, `c-footer__nav`) — 200 samo o sobě neodhalí zapomenutý `<x-layouts.app>` wrapper.

### Novinky / Článek (`/novinky`, `/novinky/{article:slug}`)

Referenční příklad prvního **reálného list+detail páru** (na rozdíl od `/partneri`/`/faq`/`/turnaje`, což jsou pořád jen preview stránky bez plného obsahu okolo).

- **Skutečná paginace, ne dekorativní.** `ui/`'s `macros/pagination.njk` jen předstírá `?page=N` odkazy nad statickým mockem (`currentPage`/`totalPages` natvrdo). My máme reálná data → `Article::paginate(9)` + `components/pagination.blade.php` čte skutečný `LengthAwarePaginator` (`$paginator->url($page)`, `->hasMorePages()` atd.) — stejné BEM třídy/markup jako `ui/`, takže žádná CSS změna, ale funkčně je to o level líp než předloha.
- **"Podobné články" jsou reálný dotaz, ne ručně vybraná trojice** jako v `ui/`'s statickém příkladu — `ArticleController::show()` bere 3 nejnovější články ze **stejné kategorie** (fallback na nejnovější celkově, když článek nemá kategorii). U článku, kde žádný jiný v kategorii není, se sekce korektně vůbec nezobrazí (`@if ($related->isNotEmpty())`).
- **Kategorie filtr taby jsou pořád dekorativní** (stejně jako v `ui/` — tam explicitně říká "search/filter je záměrně inertní"), ale postavené z reálných `ArticleCategory` záznamů + "Vše" natvrdo napřed, ne z hardcoded pole. `data-category` hodnota je `Str::slug($category->name)` za běhu — `ArticleCategory` nemá vlastní `slug` sloupec, není potřeba, dokud se filtr fakticky nezapojí.
- **Galerie zatím bez lightboxu** (GLightbox není portovaný) — dlaždice v `components/gallery.blade.php` mají navíc `target="_blank"` oproti `ui/`'s předloze, ať klik aspoň neopustí článek. Až se GLightbox portuje, `target="_blank"` zase odstranit a přidat `glightbox`/`data-gallery` zpátky.
- Nové utility soubory poprvé portované touhle dávkou: `04_utils/{spacing,backgrounds,borders}.css` (`.pt-none`/`.pb-none`, `.bg-gradient-light`, `.border-top`/`.border-bottom`) — potřebné, jakmile se sekce s různým/stejným pozadím řadí za sebe (viz `skills/ui-component-guide.md`'s "PAST" o dvojitém paddingu/borderu).
### Zprávy výboru (`/zpravodajstvi/vykonny-vybor`, `/zpravodajstvi/vykonny-vybor/{notice:slug}`)

- `components/notice-card.blade.php` mirrors `macros/card/notice.njk` (`size="md"` kompaktní řádek se šipkou | `size="lg"` větší karta s excerptem, bez šipky — na listing stránce používáme `size="lg"`, stejně jako `ui/`).
- **Detail stránka nemá vlastní komponentu** — `ui/`'s `vykonny-vybor-detail.njk` přímo znovupoužívá `articleContent()` (jen `tagPosition="inline"`, `tagColor="accent"`), takže `zpravodajstvi/vykonny-vybor-detail.blade.php` dělá to samé s `<x-article-content>` — žádný nový "notice content" widget.
- "Důležité" (`is_important` boolean) se mapuje na `tagText="DŮLEŽITÉ"`/`"Důležité"` + `tagColor="accent"` (default barva `notice-card`u i `<x-tag>` volání) — stejný mechanismus jako v `ui/` (žádná speciální `.c-notice--important` třída, jen barevný tag).
- "Kontakt" info box, který `ui/` dává do `newsGrid`'s sidebaru, je vynechaný — stejné zjednodušení jako u Novinek (žádný sidebar layout zatím neexistuje).

### Widgety (sekce stránek) → Blade views/komponenty
- Stejný princip jako makra, ale často už s reálnými daty místo mock JSON — widget přijímá Eloquent kolekci/model místo pole z `ui/src/_data/*.json`.
- Datový tvar (jména klíčů v `items`) drž pokud možno stejný jako v mock JSON, ať je port 1:1 a ne přejmenovávání polí navíc.

### Zpětný směr (výjimka)
Pokud se v `web/` objeví nutná drobná úprava (kvůli reálným datům, edge case), přenes ji **zpět** do `ui/` co nejdřív (a do mock dat), ať `ui/` zůstane aktuální referencí pro příští portování.

---

## 4. Datový model

Tvar polí vycházej z `ui/src/_data/*.json` (turnaje, kluby, herny, zebricky, kalendarUdalosti, partneri, souteze) — jsou to fakticky hotové "schéma návrhy".

* **Obrázky/loga (jeden obrázek na entitu):** obyčejný `string` sloupec (relativní cesta na disku, ne absolutní URL) + Filament `FileUpload` (`->directory('<entita>')`, `->image()`, `->visibility('public')`). Model má accessor `<pole>_url` (`Attribute::get(fn () => $this->logo ? Storage::disk('public')->url($this->logo) : null)`), který teprve v Blade dává plnou URL — viz `app/Models/Partner.php` jako referenční příklad. **Nepoužíváme Spatie Media Library** (přidali bychom komplexitu navíc — polymorfní tabulka, konverze — kterou zatím nic v projektu nevyžaduje). Až narazíme na entitu s víc obrázky (galerie u herny/článku), řešíme to jako samostatné rozhodnutí až tehdy, ne teď dopředu.
* **Řazení spravované adminem** (např. pořadí partnerů/karet na stránce): `sort_order` (`unsignedInteger`, `default(0)`), v tabulce Filament resource `->defaultSort('sort_order')->reorderable('sort_order')` (drag&drop v adminu).
* **Seed dat zrcadlících `ui/`:** zdrojové obrázky (loga, fotky) pro seed patří do `database/seeders/assets/<entita>/` (committed do gitu — jsou to skutečné projektové assety, ne runtime uploady) a seeder je při běhu kopíruje na disk `public` (`Storage::disk('public')->put(...)`) — **`storage/app/public/` samotné je gitignored** (Laravel default, runtime/regenerovatelný obsah), takže tam zdrojové soubory nikdy nedávej přímo. Referenční příklad: `database/seeders/PartnerSeeder.php` + `database/seeders/assets/partners/`.
* **Odvozená (computed) hodnota namísto ručně udržovaného pole obecně** — nejen boolean flagy (viz `Tournament::soon()` níže), stejně tak i **zobrazovací text odvoditelný z jiných sloupců** (např. český formát rozsahu data z `start_date`/`end_date`) — ruční textové pole se může rozjet od skutečných dat, computed accessor ne. Carbon s `->locale('cs')->translatedFormat('j. F Y')` už sám vrací správný český genitiv měsíce ("20. srpna 2026"), žádná vlastní tabulka skloňování není potřeba. Referenční příklad: `Tournament::dateText()` (nahradilo dřívější ruční `date_text` sloupec; pro chybějící datum má pevný fallback text "Termín bude upřesněn").
* **Odvozená (computed) hodnota namísto ručně udržovaného boolean flagu** — pokud lze hodnotu spočítat z jiných dat (typicky z data + prahu), nepřidávej sloupec, který musí někdo ručně přepínat a může se rozjet od reality. Ulož skutečná data (např. `start_date`) a spočítej odvozenou hodnotu accessorem (`Attribute::get(...)`) — Filament tabulky/formuláře umí číst i computed accessory stejně jako sloupce (jen bez `->sortable()`/`->searchable()`, které fungují jen na reálné DB sloupce). Práh/konstanta pro výpočet patří do globálního nastavení (viz bod 1), ne natvrdo do modelu. Referenční příklad: `Tournament::soon()` (`start_date` + `GeneralSettings::$tournament_soon_threshold_days`, nahradilo dřívější ruční `soon` boolean sloupec).
* **Filtrování "aktuální a nadcházející" podle data konce/začátku:** efektivní konec akce je `end_date`, a když ten chybí, `start_date` (jednodenní akce). V SQL to řeší `COALESCE(end_date, start_date) >= dnes`, plus samostatná podmínka pro záznamy zcela bez data (TBD — ty se vždy zahrnou, protože o nich nevíme, že skončily). `COALESCE` funguje stejně v SQLite i MySQL, žádná DB-specifická obezlička. Referenční příklad: `Tournament::scopeCurrentAndUpcoming()`, použito v `TournamentController::index()`.
* **Úprava ještě neuvolněné migrace:** dokud je schéma nové entity v aktivním vývoji jen lokálně (SQLite, žádná sdílená/produkční data), uprav rovnou původní `create_<entity>_table` migraci místo přidávání `alter_table` migrace navíc — čistší historie. Jakmile je něco nasazené/sdílené s reálnými daty, tohle už neplatí (pak vždy nová migrace). Po úpravě spusť `php artisan migrate:fresh --seed`.
* **Opakovaná dvojice "volný text + barva/varianta" na více záznamech → vlastní model (taxonomie), ne duplikovaný text na každém řádku.** Jakmile se stejná kategorie/štítek (název + barva) opakuje napříč záznamy (např. `tag_text`/`tag_color` na každém turnaji), extrahuj ji do vlastní tabulky s `belongsTo` vztahem — řeší to překlepy a nekonzistentní barvu pro "stejnou" kategorii a dá se to spravovat centrálně v adminu. Ve Filament formuláři použij `Select::make(...)->relationship('<vztah>', 'name')` s `->createOptionForm(...)`, ať admin může novou kategorii založit inline bez opuštění formuláře (sdílej pole s Resource formulářem té kategorie přes veřejnou `static function components(): array` metodu, ne přes duplikaci). Nízkoúrovňová Blade komponenta (karta) zůstává na obecných propech (`tagText`/`tagColor`) — mapování `$model->category->name`/`->color` na ně dělá až volající (widget/stránka), karta samotná koncept "kategorie" nezná. Referenční příklad: `TournamentCategory` + `Tournament::category()`.
* **Pořadí migrací u nové provázané tabulky:** `make:model -mf` časuje soubor na "teď", což může být PO migraci tabulky, která na něj bude odkazovat cizím klíčem — přejmenuj soubor nové migrace na dřívější timestamp (např. o pár minut před), ať `Schema::create` proběhne ve správném pořadí. Týká se to i pořadí seederů v `DatabaseSeeder` (číselník před tabulkou, co na něj odkazuje).
* **`belongsTo` vs. `belongsToMany` u taxonomie/číselníku — podle toho, jestli záznam patří vždy jen na jedno místo, nebo může na víc zároveň.** Turnaj má vždy přesně jednu kategorii → `TournamentCategory` + `belongsTo` (cizí klíč přímo na turnaji). FAQ položka ale může dávat smysl na víc místech současně (obecná stránka FAQ i konkrétní stránka) → `FaqGroup` + `belongsToMany` (pivot tabulka), ať se stejná otázka nemusí duplikovat do víc řádků, když ji chceš zobrazit na dvou místech. Pivot tabulka: `php artisan make:migration create_<a>_<b>_table` (Laravel konvence názvu: singulární jména modelů podle abecedy, podtržítkem — `faq_group_faq_item`), `foreignId(...)->constrained()->cascadeOnDelete()` na obě strany + `unique([...])`. V Blade/Filamentu se to používá stejně jako `belongsTo` (`Select::make(...)->relationship(...)->multiple()`), jen výsledek je kolekce, ne jeden model. Referenční příklad: `FaqGroup` + `FaqItem::groups()`, filtr v `FaqController` (`whereHas('groups', fn ($q) => $q->where('slug', 'obecne'))`).
* **Uzavřený/neměnný seznam hodnot → PHP backed enum, ne DB tabulka/taxonomie.** `TournamentCategory`/`FaqGroup` jsou admin-manageable (přibývají, admin je edituje) — ale český kraj nebo "sport, který herna nabízí" jsou uzavřené, dané seznamy, který se v reálném životě nemění a nikdo je nebude "spravovat" v adminu. Pro tyhle patří `enum <Name>: string implements \Filament\Support\Contracts\HasLabel` v `app/Enums/` (`getLabel()` vrací zobrazovaný text), ne další tabulka s `belongsTo`/`belongsToMany`. Filament `Select`/`CheckboxList` bere enum třídu přímo (`->options(Region::class)`), sloupec se castuje `'region' => Region::class` v modelu. Referenční příklad: `Region` (sdílený mezi `Club`/`Herna`), `Sport` (na `Herna::$sports`, viz níže), `HernaStatus` (implementuje i `HasColor` pro barevné badge v tabulce).
* **Malá, vždy pohromadě patřící, ohraničená strukturovaná data (ne nezávisle spravovaný seznam) → obyčejný JSON sloupec, ne samostatná tabulka.** `Herna::$sports` (pole hodnot z `Sport` enumu), `$hours` (pole `{day, text}` pro 7 dní) a `$gallery` (pole cest k nahraným obrázkům, přes Filament `FileUpload::make('gallery')->multiple()` — **žádná samostatná `herna_images` tabulka, žádný Spatie Media Library**, i pro víc obrázků na entitu, pokud nejde o něco, co potřebuje vlastní řazení/metadata nad rámec pořadí v poli). Cast `'sports' => 'array'` atd. Accessor `galleryUrls()` mapuje uložené relativní cesty na plné `Storage::disk('public')->url(...)` (stejný princip jako `logo_url` u jednoho obrázku, jen na celé pole).
* **Auto-generovaný `slug` pro entity s reálným per-záznamovým routováním** (na rozdíl od entit, které mají jen listing bez detailu) — sdílený `HasSlug` trait (`app/Models/Concerns/HasSlug.php`): `protected static function bootHasSlug()` s `static::creating(...)`, doplní `slug` ze zdrojového pole jen pokud není zadaný, s `-2`/`-3`... při kolizi. Zdrojové pole je defaultně `name` — pokud model nazývá svůj titulek jinak (`Article`/`Notice` mají `title`, ne `name`), přepiš `protected static function slugSourceField(): string { return 'title'; }` v modelu. Použij `use HasSlug;` v modelu + `slug` sloupec (`unique()`) v migraci.

---

## 5. Filament Resources

* Generuj přes `php artisan make:filament-resource <Model> --generate` (odvodí formulář/tabulku z DB schématu), pak dolaď: `TextInput` pro cestu k obrázku přepiš na `FileUpload` (viz bod 4), přidej `ImageColumn` do tabulky pro náhled.
* Konvence pojmenování a struktura souborů (Filament v5): `app/Filament/Resources/<Entity>/{<Entity>Resource.php, Pages/, Schemas/<Entity>Form.php, Tables/<Entity>sTable.php}` — necháváme, jak to generátor vytvoří.
* `--generate` u čerstvě vytvořeného modelu (hned po `make:model -mfs`, ještě před `php artisan migrate`) občas vrátí prázdné `Schema`/`Table` místo odvozených polí (pravděpodobně kvůli interaktivnímu dotazu na "title attribute" i přes `--no-interaction`) — pokud se to stane, napiš `Schema`/`Table` komponenty ručně podle sloupců migrace, generátor nezkoušej spouštět znovu.

---

## 6. Ověření

1. `php artisan serve` + `npm run dev` — ověřit stránku v prohlížeči vedle `ui/` verze.
2. `vendor/bin/pint --dirty --format agent` po úpravě PHP souborů (Laravel Boost guideline).
3. Testy (Pest/PHPUnit) pro novou funkcionalitu — feature testy preferované před unit testy, viz `web/CLAUDE.md`.
4. **Ověření admin resource stránek vyžaduje skutečné přihlášení, ne jen kontrolu, že route existuje** — `curl` bez session na `/admin/<resource>` vrátí `302` (redirect na login) i když je za tím rozbitý formulář/tabulka, takže to nic neřekne o tom, jestli se Blade/Livewire fakt vykreslí. `tests/Feature/AdminResourcesTest.php` řeší tohle přes `actingAs($user)->get($url)->assertOk()` pro všechny resources najednou (s jedním reálným záznamem od každého modelu, ať se vykreslí i relace/enum sloupce, ne jen prázdná tabulka) — přidej sem novou resource, jak vznikne. Vyžaduje `App\Models\User implements Filament\Models\Contracts\FilamentUser` s `canAccessPanel(): true` — bez toho Filament mimo `local` prostředí (tedy i v testech) vrátí `403` i pro platně přihlášeného uživatele.
5. Podobně `tests/Feature/PublicPagesTest.php` pro veřejné stránky — kontroluje 200 **a** přítomnost chrome markerů (`c-header__nav`, `c-footer__nav`), ne jen 200 samotné (viz "Site chrome" výše). Přidej sem novou veřejnou route, jak vznikne.
