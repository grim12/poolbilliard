# UI Component & Template Guide

> Tento návod slouží pro vývojáře a AI asistenty pracující na frontendové / UI části projektu.
> Definuje standardy pro tvorbu komponent, Nunjucks maker, CSS architektury Tailwind 4 a struktury šablon.

---

## 1. Technologický stack & Architektura

* **Šablonovací systém:** [Eleventy 3](https://www.11ty.dev/) + [Nunjucks](https://mozilla.github.io/nunjucks/) (`.njk`)
* **CSS Framework:** [Tailwind CSS v4](https://tailwindcss.com/) přes `@tailwindcss/postcss`
* **Metodika CSS:** BEM (`.c-` komponenty, `.u-` utility) psané prioritně pomocí `@apply`
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
│   │   ├── 01_base/         # Typografie, globální reset a layout
│   │   └── 02_components/   # Samostatný CSS soubor pro každou komponentu (button.css...)
│   ├── js/
│   │   └── main.js          # Hlavní JavaScript
│   ├── assets/              # Statické soubory (loga, ikony, obrázky)
│   └── index.njk            # Aktuální showcase / UI kit rozcestník
├── .eleventy.js             # Konfigurace Eleventy
├── postcss.config.js        # Konfigurace PostCSS
└── package.json
```

---

## 2. Pravidla pro tvorbu komponent

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

### Pravidlo 2: Stylování pomocí `@apply`

Styly komponent píšeme do `@layer components` s využitím Tailwind utilit přes `@apply`.

```css
/* ui/src/styles/02_components/button.css */
@layer components {
  /* Výchozí stav: Primary + Solid + MD */
  .c-button {
    @apply inline-flex items-center justify-center font-bold text-base leading-tight rounded-xl px-6 py-3.5 bg-primary-600 text-white border border-transparent shadow-xs transition-all duration-200 cursor-pointer select-none no-underline hover:bg-primary-700 active:bg-primary-800;
  }

  /* Disabled stav */
  .c-button:disabled,
  .c-button[aria-disabled="true"] {
    @apply opacity-50 cursor-not-allowed pointer-events-none shadow-none;
  }

  /* Barevné modifikátory */
  .c-button--accent {
    @apply bg-accent-600 text-white border-transparent hover:bg-accent-700 active:bg-accent-800;
  }

  /* Stylové modifikátory */
  .c-button--outline {
    @apply bg-white text-primary-600 border-primary-200 shadow-xs hover:bg-primary-50 hover:border-primary-300 active:bg-primary-100;
  }

  .c-button--accent.c-button--outline,
  .c-button--outline.c-button--accent {
    @apply bg-white text-accent-600 border-accent-200 shadow-xs hover:bg-accent-50 hover:border-accent-300 active:bg-accent-100;
  }

  /* Velikosti */
  .c-button--sm { @apply px-4 py-2 text-sm rounded-lg; }
  .c-button--lg { @apply px-8 py-4 text-lg rounded-2xl; }
}
```

> **Důležité:** Každý nový CSS soubor musí být naimportován v `ui/src/styles/style.css`:
> `@import "./02_components/button.css";`

---

### Pravidlo 3: Nunjucks Makra (`macros/<name>.njk`)

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

### Pravidlo 4: Design Tokeny v `@theme`

Všechny barvy, breakpointy a písma jsou centrálně definovány v `ui/src/styles/style.css` uvnitř `@theme { ... }`.
Používáme sémantické aliasy:
* `primary` (modrá barva svazu: 600, 700, 800...)
* `accent` (akcentní červená: 600, 700, 800...)
* `dark` (tmavé tóny pro text a tmavá tlačítka)
* `gray-*` (neutrální šedé pro pozadí, bordery a pomocné texty)

---

### Pravidlo 5: Showcase a ověření

1. Po vytvoření komponenty ji ihned naimportujte do `ui/src/index.njk` (nebo do příslušné stránky / styleguide).
2. Zobrazte všechny stavy:
   * Originální varianty z Figmy
   * Velikostní škálu (`sm`, `md`, `lg`)
   * Barevnou a stylovou matici
   * Speciální stavy (`disabled`, bez ikony apod.)
3. Vždy ověřte build: `cd ui && npm run build`.
