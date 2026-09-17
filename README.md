# Poolbilliard — Web sportovního svazu

Projekt nového webového portálu a informačního systému pro poolbilliardový svaz.

---

## Struktura projektu

Projekt je architektonicky rozdělen na dvě hlavní části:

```text
poolbilliard/
├── ui/                     # UI šablony, komponenty a design systém (1. fáze)
│   ├── src/
│   │   ├── _includes/      # Nunjucks šablony
│   │   │   ├── layouts/    # Základní layouty stránek (base.njk, ...)
│   │   │   ├── widgets/    # Velké sekce stránek (hero, tabulky, žebříčky...)
│   │   │   ├── components/ # Znovupoužitelné atomické komponenty (karty, tlačítka...)
│   │   │   └── macros/     # Nunjucks makra
│   │   ├── _data/          # Mock data (turnaje, žebříčky, hráči, kluby)
│   │   ├── styles/         # CSS architektura (Tailwind CSS 4 + PostCSS)
│   │   │   ├── 01_base/    # Základní elementy (reset, typografie, layout)
│   │   │   ├── 02_components/ # Komponentové styly BEM (.c-, .u-)
│   │   │   ├── 03_themes/  # Kontextová témata (.t-dark)
│   │   │   └── style.css   # Vstupní CSS bod s @theme definicemi
│   │   ├── js/             # Vanilla JavaScript
│   │   │   └── main.js     # Hlavní vstupní JS bod
│   │   ├── assets/         # Statické assety (obrázky, ikony)
│   │   └── index.njk       # Stránky webu / komponentový showcase
│   ├── .eleventy.js        # Konfigurace Eleventy (generátor statického webu)
│   ├── postcss.config.js   # Konfigurace Tailwind CSS v4 PostCSS pluginu
│   └── package.json
│
├── web/                    # Backend / produkční webová aplikace (Laravel + Filament) — 2. fáze
│   ├── app/
│   │   ├── Filament/       # Filament Resources (admin CRUD pro turnaje, kluby, herny...)
│   │   ├── Models/         # Eloquent modely
│   │   └── Providers/Filament/AdminPanelProvider.php
│   ├── resources/
│   │   ├── views/          # Blade šablony a komponenty — zrcadlené z ui/src/_includes
│   │   └── css/, js/       # Zrcadlené Tailwind styly a JS z ui/src/styles, ui/src/js
│   ├── database/
│   │   ├── migrations/
│   │   └── database.sqlite # Lokální dev DB (viz sekce 2. Fáze níže)
│   ├── routes/web.php
│   ├── AGENTS.md, CLAUDE.md # Laravel Boost — obecné Laravel/PHP konvence (auto-generováno, needitovat ručně)
│   └── composer.json
│
├── skills/                 # Pravidla, konvence a instrukce pro vývojáře a AI asistenty
│   ├── ui-component-guide.md  # Návod pro tvorbu UI komponent a šablon (ui/)
│   └── web-component-guide.md # Návod pro Blade komponenty, Filament resources a workflow ui/ → web/
│
├── designs/                # Podklady z grafiky a screenshoty komponent (Figma)
│   ├── components/
│   └── pages/
│
├── .gitignore              # Ignorování závislostí a build artefaktů
└── README.md               # Hlavní dokumentace projektu
```

---

## Aktuální stav

Všech 14 stránek z `designs/pages/` (Home, Kluby, Klub, Herny, Herna, Registrace herny, Kalendář, Turnaj, Pravidelný turnaj, Soutěže, Pravidla, Jak začít, Sportovní svaz, Novinky/Článek, Partneři, Zpravodajství VV) je hotovo end-to-end — návrh v `ui/`, přenesená Blade šablona ve `web/`, a kde stránka obsahuje spravovatelný obsah, i odpovídající Filament resource/nastavovací stránka v adminu.

Otevřené věci k dořešení:
* **Registrace herny** — e-mailová notifikace sekci a potvrzení odesílateli po odeslání formuláře (viz TODO v `web/app/Http/Controllers/RegistraceHernyController.php`); zatím žádný Mailable, `MAIL_MAILER=log`. Potvrzovací flash zpráva ve formuláři zůstává tak, jak je — dedikovaná potvrzovací stránka se neplánuje.
* **Testy** — pokrytí je zatím tenké vzhledem k rozsahu appky (jen pár feature testů na desítky resources/stránek).
* **Anglická verze webu** — routing/i18n scaffold hotový, viz sekce níže; obsah stránek (kromě titulků a nastavovaných modelů) se zatím z větší části dotahuje.
* **SEO/launch-readiness** — probíhá, viz níže.
* Veřejné přihlášení pro kluby/hráče zatím neexistuje, jen Filament admin panel.

### SEO / launch-readiness

Hotovo:
* **Site lock** — celý veřejný web je zamčený za přihlášením (`App\Http\Middleware\SiteLock`), stejné přihlašovací údaje jako do Filament administrace (`App\Models\User`), odemykací obrazovka na `/pristup` (`resources/views/site-lock.blade.php`, bez `ui/` protějšku — je to provozní stránka, ne navržená stránka webu). Zamčeno je vše mimo `/admin*`, `/up` a odemykací routy; výchozí chování je zamčeno všude kromě `local` a `testing` prostředí, `SITE_LOCK_ENABLED` v `.env` to jde přebít (nastavit na `false`, až půjde web ostře spustit).
* **Env-aware indexace** — `App\Support\Launch::indexable()` (řízeno `SEO_INDEXABLE`, jinak odvozeno ze stavu site locku) rozhoduje meta `robots` tag i obsah dynamické routy `/robots.txt` (nahradila statický soubor).
* **Meta description, canonical, OG/Twitter tagy** — `<x-layouts.app>` má `description`/`ogImage`/`ogType`/`canonical` props, vyplněné na všech stránkách (u dynamických stránek odvozené z obsahu záznamu).
* **JSON-LD** — sitewide `SportsOrganization` blok v layoutu, plus `NewsArticle` (články), `Article` (zprávy VV) a `SportsEvent` (turnaje s `start_date`) na příslušných stránkách.
* **Branded 404/500** (`resources/views/errors/`) — 404 používá běžný layout, 500 je záměrně statický bez DB závislosti (viz jeho docblock).
* **HTTPS v produkci** — `AppServiceProvider::boot()` vynucuje `https://` na generovaných URL (`URL::forceScheme`) v `production`; skutečný redirect příchozích HTTP requestů a trusted proxies nastavení je na tom, kdo bude řešit produkční hosting (viz komentář v kódu).
* **`sitemap.xml`** (`SeoController::sitemap()`) — statické stránky + kluby/schválené herny/turnaje/publikované články a zprávy VV, stejná viditelnostní pravidla jako mají jejich vlastní controllery. 404 dokud web není indexovatelný; `robots.txt` na něj odkazuje.
* **Favicon sada** — vygenerováno z čtvercového loga (`web/favicon.ico`, 64×64) přes `sips` (žádný ImageMagick k dispozici): `favicon.ico`, 16/32px PNG, apple-touch-icon (180px), Android Chrome ikony (192/512px) + `site.webmanifest`. 512px varianta je znatelně měkká (upscale ~8× ze 64px zdroje) — časem by chtělo ostřejší zdrojový soubor.

Zbývá:
* **TODO: sehnat větší zdrojovou ikonu/logo** (ideálně čtvercové SVG nebo alespoň 512×512 PNG) a přegenerovat `apple-touch-icon.png`/`android-chrome-*.png` z ní — současná 512px varianta je viditelně měkká, protože je upscalovaná ~8× ze 64×64 zdroje (`web/favicon.ico`).
* Analytika zatím žádná (vědomé rozhodnutí, zatím neřešeno).

### EN routing / i18n scaffold

Hotovo:
* **`/en/...` routing** s přeloženými segmenty cesty (např. `/en/clubs`, `/en/venue/{slug_en}`) — mirror každé veřejné routy, viz `routes/web.php`. Route names mají konzistentní `en.` prefix (`klub.show` ↔ `en.klub.show`), na čemž stojí generické dopočítávání hreflang/přepínače jazyka v `<x-layouts.app>` — žádná stránka to neřeší sama.
* **`App\Http\Middleware\SetLocale`** (alias `locale:en`) na `/en` skupině nastavuje `app()->setLocale('en')` pro celý request — translatable atributy modelů (`spatie/laravel-translatable`) se tím přepnou automaticky, bez úprav v controllerech/views.
* **Header/footer** — nav, přepínač jazyka (byl už navržený v `ui/`, jen neožívený), aria-labels a placeholder přeloženy přes `lang/en.json` (`__()`), odkazy vedou přes locale-aware route helper místo natvrdo českých cest.
* **Enumy** (`Region`, `Sport`, `HernaStatus`) mají anglické varianty `getLabel()` podle `app()->getLocale()` — Filament admin běží vždy v cs, takže administraci to neovlivní.
* **`App\Support\Locale::field()`** zpřístupňuje `{field}_en` sesterské vlastnosti na Settings třídách (byly připravené dřív, nečtené — viz `TranslatableTabs::makeForSettings()`), stejný fallback na cs jako u modelů. Zapojeno zatím jen do `<title>` stránek (`herny`, `kluby`, `kalendar`, `souteze`, `jak-zacit`, `pravidla`, `sportovni-svaz`).
* **`sitemap.xml`** obsahuje `en.` mirror každé URL vedle cs varianty.

Zbývá (obsahová/i18n práce, ne infrastruktura):
* **Hlubší nastavovaná pole** (hero podtitulky, tituly jednotlivých sekcí uvnitř stránek) a **repeater/array pole** (feature_cards, stats, tasks, calendar_sources...) na `_en` zatím nenapojené.
* **Natvrdo české texty uvnitř těl šablon** (nadpisy, popisky, tlačítka mimo hlavičku/patičku) — desítky řetězců napříč ~18 šablonami, potřeba systematický průchod + `__()`/`lang/en.json`.
* **Interní odkazy uvnitř stránek** (tlačítka, back-linky, stránkování) zatím nejsou locale-aware — pořád vedou na cs cestu i z EN stránky.

### Demo obsah (seedery)

`database/seeders/` obsahuje reprezentativní demo data pro každou entitu (kluby, herny, turnaje, žebříčky, články, zprávy VV, FAQ, dokumenty, partneři, jak-zacit sekce...), adaptovaná z mock dat v `ui/` — spustitelné přes `php artisan db:seed` (idempotentní, bezpečné spouštět opakovaně). Každé pole má i anglický překlad (`_en`/`title_en`/...), takže `/en/...` stránky zobrazují reálný anglický text, ne jen český fallback, u všeho, co seedery pokrývají.

⚠️ Lokální dev databáze (`web/database/database.sqlite`) je v `.gitignore` a nemá zálohu jinde než v souborovém backupu (Time Machine apod.) — `php artisan migrate:fresh` ji nenávratně smaže. Pokud se to stane, `php artisan migrate && php artisan db:seed` obnoví admin účet (`ugrin@nittin.cz`) i demo obsah, ale ne žádná ručně zadaná produkční data.

---

## 1. Fáze: Vývoj UI šablon (`ui/`)

Prototypování a tvorba frontendových šablon probíhá v adresáři `ui/`.

### Technologický stack
* **Šablonovací systém:** [Nunjucks](https://mozilla.github.io/nunjucks/) přes [Eleventy 3](https://www.11ty.dev/)
* **CSS:** [Tailwind CSS 4](https://tailwindcss.com/) + PostCSS
* **JavaScript:** Vanilla JS (ES Modules)

### Požadavky
* Node.js 20+
* npm

### Spuštění vývoje
```bash
cd ui
npm install
npm run dev
```
Lokální vývojový server se spustí na adrese `http://localhost:8080` (s live reloadem a automatickou kompilací CSS).

### Sestavení (Build)
```bash
cd ui
npm run build
```
Vygeneruje optimalizovaný statický web do složky `ui/_site/` a zkompilované CSS do `ui/src/css/style.css`.

---

## 2. Fáze: Backend aplikace (`web/`)

Do složky `web/` je integrován backendový framework Laravel + Filament (admin panel).

### Technologický stack
* **Framework:** [Laravel 13](https://laravel.com/)
* **Admin panel:** [Filament v5](https://filamentphp.com/)
* **PHP:** 8.5 (Homebrew — `brew install composer` si PHP 8.5 vytáhne jako závislost)
* **Databáze (lokálně):** SQLite (`database/database.sqlite`) — zatím, pro rychlý start bez závislosti na běžícím DB serveru. Až bude potřeba testovat proti stejnému enginu jako produkce (MySQL, spravováno přes phpMyAdmin u hostingu), přepneme `.env` na MySQL (lokálně např. přes MAMP).
* **AI asistence:** [Laravel Boost](https://github.com/laravel/boost) — generuje/aktualizuje `web/AGENTS.md` a `web/CLAUDE.md` s obecnými Laravel/PHP/testing konvencemi (needitovat ručně, spravuje `php artisan boost:install`). Projektově specifické konvence (Blade komponenty, Filament resources, workflow `ui/` → `web/`) jsou v [`skills/web-component-guide.md`](skills/web-component-guide.md).

### Požadavky
* PHP 8.3+ (v projektu použito 8.5)
* [Composer](https://getcomposer.org/)
* Node.js + npm (pro Vite build CSS/JS)

### Spuštění vývoje
```bash
cd web
composer install
npm install
php artisan serve      # http://localhost:8000
npm run dev             # Vite dev server pro CSS/JS (samostatně, nebo `composer run dev` spustí obojí + queue listener najednou)
```
Admin panel běží na `http://localhost:8000/admin`. Přihlašovací účet se zakládá přes:
```bash
php artisan make:filament-user
```

### `ui/` a `web/` — směr workflow

`ui/` a `web/` **nejsou dva nezávislé projekty se sdíleným zdrojem** — `ui/` je rychlý, samostatný sandbox pro návrh a ladění UI (Eleventy dev server, live reload, mock data, žádná závislost na PHP/DB), `web/` je zrcadlená produkční implementace v Blade.

> ⚠️ **Závazné pravidlo: veškeré úpravy vzhledu/UI se dělají nejprve v `ui/`, teprve hotové (odladěné, schválené) se ručně přenášejí (portují) do `web/` jako Blade komponenty/views.**
> - `ui/` je zdroj pravdy pro vzhled — nikdy needitujeme Blade šablonu jako první místo pro vizuální změnu.
> - Přenos je **ruční zrcadlení**, ne sdílený include/symlink — Nunjucks makro/widget a jeho Blade protějšek jsou dva samostatné soubory, které je nutné udržovat v souladu.
> - Po portování komponenty do `web/` si oba stavy (ui/ i web/) musí vizuálně odpovídat — postup a konvence pro Blade stranu jsou ve [`skills/web-component-guide.md`](skills/web-component-guide.md).
> - Pokud se v `web/` najde nutná drobná úprava (např. kvůli reálným datům), přenáší se **zpět** do `ui/` co nejdřív, aby `ui/` zůstal aktuální referencí — neroztéká se vzhled do dvou verzí pravdy.

---

## Pravidla, konvence a instrukce pro AI asistenty

Projekt je navržen tak, aby na něm mohl kdokoliv plynule navázat – ať už samostatně, nebo s libovolným AI asistentem (Claude, Junie, Cursor, Copilot atd.).

Kompletní metodika a detailní kódové vzory jsou uloženy ve složce **`skills/`**:
* **[`skills/ui-component-guide.md`](skills/ui-component-guide.md)** – Podrobný návod pro tvorbu komponent, Nunjucks maker, kompozičních BEM tříd a Tailwind 4 stylů s `@apply` (`ui/`).
* **[`skills/web-component-guide.md`](skills/web-component-guide.md)** – Návod pro Blade komponenty, Eloquent modely, Filament resources a workflow přenosu hotových úprav z `ui/` do `web/`.

### Rychlý přehled klíčových pravidel:
1. **Kompoziční (ortogonální) BEM třídy:** Výchozí třída komponenty (např. `.c-button`) nese kompletní výchozí vzhled (primary + solid + md). Modifikátory (`--accent`, `--outline`, `--sm`, `--lg`...) pouze přepisují konkrétní vlastnosti.
2. **Stylování přes `@apply`:** Styly komponent píšeme do `ui/src/styles/02_components/<component>.css` v `@layer components` pomocí Tailwind utilit.
3. **Nunjucks Makra:** Pro komponenty s logikou a parametry vytváříme makra v `ui/src/_includes/macros/<component>.njk`.
4. **Showcase & Testování:** Každá nová komponenta se ihned zařazuje do přehledu v `ui/src/index.njk` se všemi stavy a variantami.
5. **Dokumentace se udržuje průběžně:** `README.md` (hlavně sekce „Aktuální stav") a soubory ve `skills/` popisují skutečný stav a pravidla projektu, ne stav ke dni založení. Po dokončení netriviální úlohy (nová stránka, změna workflow, nové pravidlo) je uprav tak, aby odpovídaly realitě — ať už na tom pracuje člověk, nebo AI asistent.
