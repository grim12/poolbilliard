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
│   │   │   ├── 02_components/ # Komponentové styly BEM (.c-, .o-, .u-)
│   │   │   └── style.css   # Vstupní CSS bod s @theme definicemi
│   │   ├── js/             # Vanilla JavaScript
│   │   │   └── main.js     # Hlavní vstupní JS bod
│   │   ├── assets/         # Statické assety (obrázky, ikony)
│   │   └── index.njk       # Stránky webu
│   ├── .eleventy.js        # Konfigurace Eleventy (generátor statického webu)
│   ├── postcss.config.js   # Konfigurace Tailwind CSS v4 PostCSS pluginu
│   └── package.json
│
├── web/                    # Backend / produkční webová aplikace (Laravel + Filament)
│   └── (připraveno pro inicializaci backendové aplikace)
│
├── .junie/                 # AI guidelines, skills a konvence projektu
│   └── skills/
│       └── ui-template-guide.md
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

## Pravidla a konvence pro vývoj UI

* **CSS / BEM:** Pro vlastní komponenty používáme BEM s prefixy:
  * `.c-` pro komponenty (např. `.c-card`, `.c-btn`)
  * `.o-` pro layoutové objekty (např. `.o-container`)
  * `.u-` pro utility třídy
* **Tailwind 4:** Všechny design tokeny (barvy, písma, breakpointy) definujeme v bloku `@theme` v `ui/src/styles/style.css`.
* **Nunjucks šablony:** Využíváme dědičnost přes `{% extends %}` a znovupoužitelné části přes `{% include %}` / makra `{% import %}`.
