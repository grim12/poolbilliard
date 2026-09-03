# UI Component & Template Guide

> Tento návod slouží pro vývojáře a AI asistenty pracující na frontendové / UI části projektu.
> Definuje standardy pro tvorbu komponent, Nunjucks maker, CSS architektury Tailwind 4 a struktury šablon.

---

## 1. Technologický stack & Architektura

* **Šablonovací systém:** [Eleventy 3](https://www.11ty.dev/) + [Nunjucks](https://mozilla.github.io/nunjucks/) (`.njk`)
* **CSS Framework:** [Tailwind CSS v4](https://tailwindcss.com/) přes `@tailwindcss/postcss`
* **Metodika CSS:** BEM (`.c-` komponenty, `.u-` utility, `.t-` themes) psané prioritně pomocí `@apply`
* **Písmo:** [Archivo](https://fonts.google.com/specimen/Archivo) (Google Fonts)
* **JavaScript:** Vanilla JS (ES Modules) v `ui/src/js/main.js`

### Adresářová struktura (`ui/`)

```text
ui/
├── src/
│   ├── _includes/
│   │   ├── layouts/         # Layouty stránek (base.njk, page-header.njk...)
│   │   ├── widgets/         # Větší sekce stránek (hero, turnaje, žebříček...)
│   │   ├── components/      # Znovupoužitelné části / šablony
│   │   └── macros/          # Nunjucks makra s parametry (button.njk, card.njk...)
│   ├── _data/               # Mock data (turnaje.json, hraci.json...)
│   ├── styles/
│   │   ├── style.css        # Vstupní bod, @theme definice tokenů, @importy
│   │   ├── 01_base/         # Typografie (typography.css), globální reset a layout
│   │   ├── 02_components/   # Samostatný CSS soubor pro každou komponentu (button.css...)
│   │   └── 03_themes/       # Témata sekcí (dark.css pro .t-dark...)
│   ├── js/
│   │   └── main.js          # Hlavní JavaScript
│   ├── assets/              # Statické soubory (loga, ikony, obrázky)
│   └── index.njk            # Aktuální showcase / UI kit rozcestník
├── .eleventy.js             # Konfigurace Eleventy
├── postcss.config.js        # Konfigurace PostCSS
└── package.json
```

---

## 2. Typografie a Témata

### Typografická pravidla (`01_base/typography.css`)
* Všechny nadpisy mají párové třídy (např. `h1, .h1 { ... }`), aby šlo styl přiřadit libovolnému HTML tagu bez nutnosti používat `<h1>`.
* **Velikosti písma a řádkování:** Jsou nadefinovány v `@theme` (`--text-*`, `--leading-*`, `--tracking-*`) a aplikují se přes responsivní `@apply`:
  * **H1 / .h1:** `@apply font-black tracking-tight-2 text-text-main leading-h1 text-4xl sm:text-6xl lg:text-7xl mb-6;` (32px mobile / 40px tablet / 52px desktop)
  * **H2 / .h2:** `@apply font-black tracking-tight-2 text-text-main leading-h2 text-3xl sm:text-5xl lg:text-6xl mb-5;` (28px mobile / 34px tablet / 40px desktop)
  * **H3 / .h3:** `@apply font-extrabold tracking-tight-2 text-text-main leading-h3 text-2xl sm:text-3xl lg:text-4xl mb-4;` (24px mobile / 28px tablet / 32px desktop)
  * **p / .p:** `@apply font-normal text-text-muted tracking-normal text-base leading-body mb-4;` (16px)
  * **.p--lg:** `@apply font-medium text-lg leading-body mb-5;` (18px)
* **Výchozí barva textu / nadpisů:** `#10202D` (`--color-text-main`)
* **Pravidlo pro první a poslední element (`:first-child`, `:last-child`):** Textové bloky mají automatický reset horního a dolního marginu:
  * `&:first-child { @apply mt-0; }` — první prvek v kontejneru nemá horní margin.
  * `&:last-child { @apply mb-0; }` — poslední prvek v kontejneru nemá spodní margin.

### Témata (`03_themes/`)
* Kontextová témata používají prefix `.t-` (např. `.t-dark`).
* **`.t-dark`:** Pro sekce s tmavým pozadím. Automaticky přebarvuje text na světlý, nadpisy na bílé a oddělovače na poloprůhledné bílé linky.

---

## 3. Pravidla pro tvorbu komponent

Každá nová komponenta se skládá ze 3 propojených částí:
1. **CSS soubor** v `ui/src/styles/02_components/<component>.css`
2. **Nunjucks makro** v `ui/src/_includes/macros/<component>.njk`
3. **Showcase / dokumentace** v `ui/src/index.njk` (příp. `styleguide.njk`)

---

### Pravidlo 1: Kompoziční (ortogonální) BEM třídy

* Výchozí třída komponenty (např. `.c-button`) nese **kompletní výchozí styl** (např. Primary + Solid + MD).
* Modifikátory jsou rozděleny do nezávislých os (Barva, Styl, Velikost) a pouze **přepisují** výchozí vlastnosti:
  * **Barva:** `.c-button--accent`, `.c-button--dark`
  * **Vzhled/Styl:** `.c-button--outline`, `.c-button--link`
  * **Velikost:** `.c-button--sm`, `.c-button--lg`
  * **Doplňky:** `.c-button--with-arrow`
* Vyhýbejte se monolitickým kombinacím jako `.c-button--primary-outline-lg`.

---

### Pravidlo 2: Struktura složitějších komponent (Karty, Moduly)

U složitějších komponent s mnoha variantami (např. karty `.c-card`):
1. **Adresář maker:** `ui/src/_includes/macros/card/`
   * `article-main.njk`, `article-compact.njk`, `tournament.njk` atd.
   * Souhrnný export v `macros/card.njk`.
2. **Adresář stylů:** `ui/src/styles/02_components/card/`
   * `article-main.css`, `article-compact.css` atd.
   * `card.css` definuje obecné `.c-card` a importuje dílčí soubory.
3. **BEM subelementy (`__`):**
   * Elementy pevně svázané s komponentou používají dvojité podtržítko: `.c-card__media`, `.c-card__overlay`, `.c-card__body`, `.c-card__meta`, `.c-card__date`, `.c-card__title`, `.c-card__link`.
3b. **Celoplošně klikatelná karta — `article` obaluje `a`, ne naopak:**
   * Root komponenty zůstává sémantický `<article class="c-card ...">` (samostatná jednotka obsahu). Hned uvnitř je **jediný** `<a class="c-card__link" href="…">`, který obaluje úplně všechno ostatní (média, overlay, body, meta, nadpis) — `<a>` smí dle HTML5 obsahovat blokové elementy (transparentní content model), takže tohle je validní.
   * Opačně (`<a>` jako root obalující `<article>`) nedává smysl — `article` reprezentuje samostatný kus obsahu, ne "obsah odkazu".
   * Nadpis (`.c-card__title`) uvnitř `.c-card__link` je pak čistý text/heading **bez vlastního vnořeného `<a>`** — žádný jiný interaktivní/odkazový prvek uvnitř karty už být nesmí (vnořené `<a>` v `<a>` je nevalidní HTML). Proto `tag()` makro uvnitř karet voláme vždy bez `url` (vykreslí se jako `<span>`, ne `<a>`).
   * **Accessible name:** protože `<a>` obaluje obrázek + tag + datum + nadpis, bez zásahu by screen reader přečetl název odkazu jako spojení všech těchto textů. Proto `.c-card__link` vždy nese `aria-label="{{ title }}"` (accessible name = jen název článku) a `<img>` uvnitř karty má prázdný `alt=""` (dekorativní, informace je už v `aria-label` odkazu).
   * `.c-card__link` má sdílené základní styly v `card.css` (`block h-full w-full` + viditelný `:focus-visible` ring — nikdy nedávat `focus:outline-none` bez náhrady). Konkrétní varianta karty může tyto styly přepsat (např. `article-compact.css` mění `.c-card__link` na `flex items-stretch` pro horizontální layout) — stejný princip jako přepisování vnořených komponent v bodě 4.
   * Vizuální `:hover` stavy (zvětšení obrázku, změna barvy nadpisu) doplňujeme i o `:focus-within` na root kartě, aby stejný efekt dostal i uživatel ovládající web klávesnicí.
4. **Vnořené samostatné komponenty:**
   * Uvnitř karet se běžně vnořují samostatné komponenty (např. `.c-tag`, `.c-button`).
   * Nadřazená karta (parent) může v případě potřeby styly vnořeného childu přepsat v rámci svého CSS bloku.
5. **Vzor "flush/bleed" média u horizontálních (kompaktních) karet:**
   * U horizontálních karet (`article-compact` a podobné) obrázek v designu vždy "vytéká" až k okraji karty (nahoře/vlevo/dole), bez paddingu kolem něj — padding má pouze textový `.c-card__body`.
   * Implementace: root karty je `flex items-stretch overflow-hidden rounded-*` (bez paddingu a bez borderu na rootu), `.c-card__media` má pevnou šířku (`w-24 sm:w-28 md:w-32`) a **minimální výšku odpovídající šířce** (`min-h-24 sm:min-h-28 md:min-h-32`), `.c-card__body` nese `p-4 sm:p-5`. Zaoblení rohů obrázku řeší `overflow-hidden` na rootu — obrázek samotný nemá vlastní `rounded-*`.
   * `min-h-*` na `.c-card__media` je nutné, jinak se karta bez načteného/rozbitého obrázku (nebo před dokončením `loading="lazy"`) zhroutí na nulovou výšku (flex `align-items: stretch` počítá výšku řádku z obsahu, ne z `w-full h-full` obrázku) → vizuální "poskočení" layoutu (CLS).

---

### Pravidlo 3: Stylování pomocí `@apply`, explicitní názvy tříd a responsivita

Styly komponent píšeme do `@layer components` s využitím Tailwind utilit přes `@apply`.

> ⚠️ **ZÁVAZNÁ PRAVIDLA PRO CSS, NÁZVY TŘÍD A RESPONZIVITU:**
> - **NIKDY nepoužívejte ruční `@media` dotazy** (např. `@media (min-width: 640px)`). V `@media` nelze použít CSS proměnné a vede to k nesjednoceným breakpointům. Místo toho **VŽDY používejte responzivní utility přes `@apply`**, např.:
>   - `@apply text-3xl sm:text-4xl lg:text-5xl;`
>   - `@apply px-4 md:px-6 xl:px-8;`
>   - `@apply flex-col md:flex-row;`
> - **NIKDY nepoužívejte zřetězení názvů přes `&--modifier`** uvnitř bloku (např. `.c-button { &--accent {} }`). Vždy pište **celý název třídy** (`.c-button--accent {}`). Díky tomu funguje přímé full-text vyhledávání tříd v celém projektu a kód je přehledný.
> - **Vnořování (CSS Nesting) používáme tam, kde to dává logický kontextový smysl:**
>   - Kontextová témata a obalovače: `.t-dark { .c-button {} .c-button--primary {} }`
>   - HTML tagy v tématech: `.t-dark { h1, .h1 { ... } }`
>   - Pseudo-třídy a stavy: `&:disabled`, `&:hover` uvnitř konkrétní třídy
>   - Vnořené elementy / potomci: `.c-button--with-arrow { svg { ... } }`

**Příklad správně:**
```css
/* ✅ SPRÁVNĚ */
.t-dark {
  .c-button {}
  .c-button--primary {}
}

.c-button {
  @apply inline-flex items-center ...;

  &:disabled {
    @apply opacity-50;
  }
}

.c-button--primary {
  @apply bg-primary-600 text-white;
}

.c-button--accent {
  @apply bg-accent-600 text-white;
}
```

**Příklad špatně:**
```css
/* ❌ ŠPATNĚ: SASS-style zřetězení názvů */
.c-button {
  &--primary {}
  &--accent {}
}
```

**Ukázka kompletní komponenty (`ui/src/styles/02_components/button.css`):**
```css
@layer components {
  /* Výchozí stav: Primary + Solid + MD */
  .c-button {
    @apply inline-flex items-center justify-center font-bold text-base leading-tight rounded-xl px-6 py-3.5 bg-primary-600 text-white border border-transparent shadow-xs transition-all duration-200 cursor-pointer select-none no-underline hover:bg-primary-700 active:bg-primary-800;

    /* Disabled stav */
    &:disabled,
    &[aria-disabled="true"] {
      @apply opacity-50 cursor-not-allowed pointer-events-none shadow-none;
    }
  }

  /* Barevné modifikátory (celé názvy tříd) */
  .c-button--primary {
    @apply bg-primary-600 text-white border-transparent hover:bg-primary-700 active:bg-primary-800;
  }

  .c-button--accent {
    @apply bg-accent-600 text-white border-transparent hover:bg-accent-700 active:bg-accent-800;
  }

  .c-button--dark {
    @apply bg-dark text-white border-transparent hover:bg-dark-hover active:bg-slate-950;
  }

  /* Stylové modifikátory */
  .c-button--outline {
    @apply bg-white text-primary-600 border-primary-200 shadow-xs hover:bg-primary-50 hover:border-primary-300 active:bg-primary-100;

    &.c-button--accent {
      @apply text-accent-600 border-accent-200 hover:bg-accent-50 hover:border-accent-300 active:bg-accent-100;
    }
  }

  /* Velikosti */
  .c-button--sm { @apply px-4 py-2 text-sm rounded-lg; }
  .c-button--lg { @apply px-8 py-4 text-lg rounded-2xl; }

  /* Vnořené elementy & ikony */
  .c-button--with-arrow {
    @apply gap-3;

    svg,
    .c-button_icon {
      @apply transition-transform duration-200 shrink-0;
    }

    &:hover {
      .c-button_icon,
      svg {
        @apply translate-x-0.5;
      }
    }
  }
}
```

Podobně i v tématech (`03_themes/dark.css`):
```css
.t-dark {
  @apply bg-dark text-white;

  h1, .h1,
  h2, .h2,
  h3, .h3 {
    @apply text-white;
  }

  p, .p, .p--lg {
    @apply text-slate-300;
  }
}
```

> **Důležité:** Každý nový CSS soubor musí být naimportován v `ui/src/styles/style.css`:
> `@import "./02_components/button.css";`

> ⚠️ **PAST: `aspect-*` + `min-h-*` na block elementu bez `w-full` rozbíjí šířku!**
> Pokud má block-level element (např. `<article>` bez `width`) zároveň `aspect-[…]` **a** `min-h-[…]`/`min-h-*`, a obsah je nižší než `min-h`, prohlížeč dopočítá **šířku** z poměru stran a použité výšky (`min-h`) místo toho, aby vyplnil šířku rodiče — element pak přeteče mimo kontejner (typicky viditelné na mobilu, kde je `min-h` relativně vysoké vůči šířce). Fix: k `aspect-*`/`min-h-*` vždy přidat explicitní `w-full`, aby šířka byla definovaná hodnota a ne `auto`.
> ```css
> /* ❌ ŠPATNĚ: na úzkém viewportu element přeteče (šířka se dopočítá z min-h × aspect-ratio) */
> .c-card--article-main {
>   @apply block relative aspect-[4/3] min-h-[360px];
> }
> /* ✅ SPRÁVNĚ */
> .c-card--article-main {
>   @apply block w-full relative aspect-[4/3] min-h-[360px];
> }
> ```

---

### Pravidlo 4: Nunjucks Makra (`macros/<name>.njk`)

Makra musí být čistá, dobře typovaná v komentáři a nesmí generovat zbytečné redundantní třídy:

```jinja2
{#
    button(text, url, color, variant, size, hasArrow, type, iconSize, extraClass, target, ariaLabel, disabled)
    - url: zadáno -> vygeneruje <a>, jinak <button type="{{ type }}">
    - color: "primary" (default) | "accent" | "dark"
    - variant: "solid" (default) | "outline" | "link"
    - size: "md" (default) | "sm" | "lg"
    - hasArrow: true (default) | false
#}
{% macro button(
    text,
    url="",
    color="primary",
    variant="solid",
    size="md",
    hasArrow=true,
    type="button",
    iconSize=16,
    extraClass="",
    target="",
    ariaLabel="",
    disabled=false
) -%}
    {%- set colorClass = ("c-button--" + color) if (color and color != "primary") else "" -%}
    {%- set variantClass = ("c-button--" + variant) if (variant and variant != "solid") else "" -%}
    {%- set sizeClass = ("c-button--" + size) if (size and size != "md") else "" -%}
    {%- set arrowClass = "c-button--with-arrow" if hasArrow else "" -%}

    {%- set classes = ["c-button", colorClass, variantClass, sizeClass, arrowClass, extraClass] | select | join(" ") -%}

    {%- if url -%}
        <a href="{{ url }}" class="{{ classes }}"{% if target %} target="{{ target }}" rel="noopener noreferrer"{% endif %}{% if ariaLabel %} aria-label="{{ ariaLabel }}"{% endif %}{% if disabled %} aria-disabled="true" tabindex="-1"{% endif %}>
            <span>{{ text | safe }}</span>
            {%- if hasArrow -%}
                <svg class="c-button_icon" width="{{ iconSize }}" height="{{ iconSize }}" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 3.5L10.5 8L6 12.5"/>
                </svg>
            {%- endif -%}
        </a>
    {%- else -%}
        <button type="{{ type }}" class="{{ classes }}"{% if ariaLabel %} aria-label="{{ ariaLabel }}"{% endif %}{% if disabled %} disabled{% endif %}>
            <span>{{ text | safe }}</span>
            {%- if hasArrow -%}
                <svg class="c-button_icon" width="{{ iconSize }}" height="{{ iconSize }}" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 3.5L10.5 8L6 12.5"/>
                </svg>
            {%- endif -%}
        </button>
    {%- endif -%}
{%- endmacro %}
```

---

### Pravidlo 5: Design Tokeny v `@theme`

Všechny barvy, breakpointy a písma jsou centrálně definovány v `ui/src/styles/style.css` uvnitř `@theme { ... }`.
Používáme sémantické aliasy:
* `primary` (modrá barva svazu: 600, 700, 800...)
* `accent` (akcentní červená: 600, 700, 800...)
* `dark` (tmavé tóny pro text a tmavá tlačítka)
* `gray-*` (neutrální šedé pro pozadí, bordery a pomocné texty)

---

### Pravidlo 6: Showcase a ověření

1. Po vytvoření komponenty ji ihned naimportujte do `ui/src/index.njk` (nebo do příslušné stránky / styleguide).
2. Zobrazte všechny stavy:
   * Originální varianty z Figmy
   * Velikostní škálu (`sm`, `md`, `lg`)
   * Barevnou a stylovou matici
   * Speciální stavy (`disabled`, bez ikony apod.)
3. Vždy ověřte build: `cd ui && npm run build`.
