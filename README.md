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
