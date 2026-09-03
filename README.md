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
│   │   │   └── style.css   # Vstupní CSS bod s @theme definicemi
│   │   ├── js/             # Vanilla JavaScript
│   │   │   └── main.js     # Hlavní vstupní JS bod
│   │   ├── assets/         # Statické assety (obrázky, ikony)
│   │   └── index.njk       # Stránky webu / komponentový showcase
│   ├── .eleventy.js        # Konfigurace Eleventy (generátor statického webu)
│   ├── postcss.config.js   # Konfigurace Tailwind CSS v4 PostCSS pluginu
│   └── package.json
│
├── web/                    # Backend / produkční webová aplikace (Laravel + Filament)
│   └── (připraveno pro inicializaci backendové aplikace)
│
├── skills/                 # Pravidla, konvence a instrukce pro vývojáře a AI asistenty
│   └── ui-component-guide.md # Návod pro tvorbu UI komponent a šablon
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

V další fázi bude do složky `web/` integrován backendový framework (Laravel + Filament admin). Šablony a komponenty z `ui/` budou přímo převzaty nebo zrcadleny do Blade komponent.

---

## Pravidla, konvence a instrukce pro AI asistenty

Projekt je navržen tak, aby na něm mohl kdokoliv plynule navázat – ať už samostatně, nebo s libovolným AI asistentem (Claude, Junie, Cursor, Copilot atd.).

Kompletní metodika a detailní kódové vzory jsou uloženy ve složce **`skills/`**:
* **[`skills/ui-component-guide.md`](skills/ui-component-guide.md)** – Podrobný návod pro tvorbu komponent, Nunjucks maker, kompozičních BEM tříd a Tailwind 4 stylů s `@apply`.

### Rychlý přehled klíčových pravidel:
1. **Kompoziční (ortogonální) BEM třídy:** Výchozí třída komponenty (např. `.c-button`) nese kompletní výchozí vzhled (primary + solid + md). Modifikátory (`--accent`, `--outline`, `--sm`, `--lg`...) pouze přepisují konkrétní vlastnosti.
2. **Stylování přes `@apply`:** Styly komponent píšeme do `ui/src/styles/02_components/<component>.css` v `@layer components` pomocí Tailwind utilit.
3. **Nunjucks Makra:** Pro komponenty s logikou a parametry vytváříme makra v `ui/src/_includes/macros/<component>.njk`.
4. **Showcase & Testování:** Každá nová komponenta se ihned zařazuje do přehledu v `ui/src/index.njk` se všemi stavy a variantami.
