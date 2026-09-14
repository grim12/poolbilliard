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

**Referenční příklady end-to-end features** (DB → Model → Filament Resource → Blade → route),
zatím **bez site chrome** (header/footer/page-hero/newsletter — portuje se samostatně, až na
řadu přijde `layouts/base.njk`/`header.njk`/`footer.njk`):
* `Partner` — jeden obrázek na entitu (`FileUpload` + `logo_url` accessor), `sort_order`
  s drag&drop řazením. `app/Models/Partner.php`, `database/seeders/PartnerSeeder.php`,
  `app/Filament/Resources/Partners/`, `resources/views/partneri.blade.php` +
  `resources/views/components/partner-card.blade.php`, route `/partneri`.
* `FaqItem` — rich-text pole (`RichEditor`), akordeon s JS chováním portovaným z `main.js`.
  `app/Models/FaqItem.php`, `database/seeders/FaqItemSeeder.php`,
  `app/Filament/Resources/FaqItems/`, `resources/views/faq.blade.php` +
  `resources/views/components/faq.blade.php`, route `/faq`.
* `Tournament` — enum-like sloupce (`tag_color` jako `Select` s pevnou nabídkou, `badge`/`soon`
  jako `boolean` + Filament `Toggle`/`IconColumn`). `app/Models/Tournament.php`,
  `database/seeders/TournamentSeeder.php`, `app/Filament/Resources/Tournaments/`,
  `resources/views/turnaje.blade.php` + `resources/views/components/{tournament-card,tournaments}.blade.php`,
  route `/turnaje`. **Poznámka:** `ui/` nemá pro tenhle grid samostatnou stránku (jen homepage
  sekce + plný Kalendář s JS filtry) — `/turnaje` je dočasná ukázková route, ne 1:1 port
  existující `ui/` stránky.
* Atomické komponenty `tag` a `button` (`resources/views/components/{tag,button}.blade.php`)
  jsou portované jako samostatné, znovupoužitelné Blade komponenty (ne duplikované do každého
  widgetu) — viz jejich použití v `info-panel.blade.php` i `tournament-card.blade.php`.
* **Podmíněné třídy v Blade:** pro `class="a @if(...) b @endif"` použij radši `@class(['a', 'b' => $podminka])` direktivu (nedělá nadbytečné mezery v atributu) — viz `tournament-card.blade.php`.
* **Brand ikonky (Simple Icons) ještě nejsou portované** — `tournaments.blade.php` proto zatím vynechává poznámku "sledujte přímé přenosy... ČMBS TV" (potřebuje YouTube brand ikonu). Až bude potřeba, doplnit balíček a poznámku zpět.

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

---

## 3. Workflow: přenos z `ui/` do `web/`

Viz i pravidlo v hlavním `README.md`. `ui/` je zdroj pravdy pro vzhled — v `web/` se nikdy nedělá vizuální změna jako první.

### Postup portování jedné komponenty (makro → Blade komponenta)

1. Otevři hotové/odladěné makro v `ui/src/_includes/macros/<name>.njk` a jeho CSS v `ui/src/styles/02_components/<name>.css` (nebo `section/<name>.css` u `.c-section--*` variant).
2. Vytvoř Blade protějšek v `web/resources/views/components/<name>.blade.php`. Parametry makra (`{% macro foo(a, b="x") %}`) → `@props(['a', 'b' => 'x'])`.
3. **BEM třídy (`.c-*`, `.u-*`, `.t-*`) se přenášejí beze změny** — stejný název třídy v Blade i Nunjucks, ať CSS soubor (bod 4) sedí na obě strany beze změny.
4. Zkopíruj příslušný CSS soubor 1:1 do `web/resources/css/<stejná cesta jako v ui/>` (žádné přepisování na jinou metodiku — pořád Tailwind 4 + `@apply` + BEM, viz `skills/ui-component-guide.md` Pravidlo 3).
5. Přidej `@import './<stejná cesta>';` do `web/resources/css/app.css` (soubory, případně `02_components/section.css`, mají u sebe komentář, že seznam importů odráží jen to, co je na `web/` straně už portované — udržuj ho v souladu s tím, co skutečně existuje, ne s celým seznamem z `ui/`).
6. Pokud makro obsahuje `{% icon %}`/`{% brandIcon %}` shortcode (Heroicons), použij Blade ekvivalent — `blade-ui-kit/blade-heroicons` je už závislost Filamentu. **Projekt preferuje "mini" (`m-`) geometrii i pro větší ikony** (stejná konvence jako `ui/`, viz `skills/ui-component-guide.md` Pravidlo 7): `<x-heroicon-m-chevron-down width="20" height="20" class="..." />`. Když je název ikony proměnná (např. `button()`'s `icon`/`leadingIcon` parametr), použij `<x-dynamic-component :component="'heroicon-m-'.$icon" :width="$iconSize" :height="$iconSize" />` — viz `components/button.blade.php`. Brand ikonky (`{% brandIcon %}`, Simple Icons) ještě nemáme portované — až budou potřeba, doplnit sem.
7. Ověř vizuálně vedle sebe (`ui/` dev server vs. `web/` dev server) — musí sedět 1:1.

**Design tokeny** (barvy, radius, fonty, stíny) jsou v `web/resources/css/app.css` uvnitř `@theme { ... }` — je to ruční zrcadlo `@theme` bloku z `ui/src/styles/style.css`. Změníš-li token v `ui/`, proveď stejnou změnu i tady (obě místa výslovně na sebe odkazují komentářem).

**`extraClass` parametr maker se do Blade nepřenáší jako samostatný prop** — Blade má nativní mechanismus přesně pro tohle (`$attributes`), použij ho: komponenta v základu vrací `{{ $attributes->merge(['class' => $classes]) }}` na root elementu, volající pak přidá extra třídy prostě jako `class="..."` atribut na tag komponenty (`<x-tag ... class="mb-2" />`), stejně jako `id`, `data-*` apod. Viz `components/tag.blade.php`, `components/button.blade.php`.

**Rich-text pole** (makro používalo `| safe` filtr na hodnotu z dat, např. FAQ odpověď): v DB obyčejný `text` sloupec s uloženým HTML, ve Filament formuláři `RichEditor::make(...)` (ne `Textarea`/`TextInput` — administrátor needituje HTML ručně), v Blade vypsat neescapovaně přes `{!! $value !!}` (Blade ekvivalent `| safe`). Viz `FaqItemForm` + `components/faq.blade.php`.

**JS chování widgetu** (ne site chrome): pokud makro/widget má v `main.js` vlastní, samostatný blok chování (např. FAQ akordeon `[data-faq-toggle]`), zkopíruj jen ten blok do `web/resources/js/app.js` (ne celý `main.js` najednou — ten obsahuje i header/mobile-nav JS, které čeká na port site chrome). Odkazuj v komentáři na zdrojový blok v `ui/src/js/main.js`.

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

---

## 5. Filament Resources

* Generuj přes `php artisan make:filament-resource <Model> --generate` (odvodí formulář/tabulku z DB schématu), pak dolaď: `TextInput` pro cestu k obrázku přepiš na `FileUpload` (viz bod 4), přidej `ImageColumn` do tabulky pro náhled.
* Konvence pojmenování a struktura souborů (Filament v5): `app/Filament/Resources/<Entity>/{<Entity>Resource.php, Pages/, Schemas/<Entity>Form.php, Tables/<Entity>sTable.php}` — necháváme, jak to generátor vytvoří.

---

## 6. Ověření

1. `php artisan serve` + `npm run dev` — ověřit stránku v prohlížeči vedle `ui/` verze.
2. `vendor/bin/pint --dirty --format agent` po úpravě PHP souborů (Laravel Boost guideline).
3. Testy (Pest/PHPUnit) pro novou funkcionalitu — feature testy preferované před unit testy, viz `web/CLAUDE.md`.
