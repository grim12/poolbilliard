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
  `app/Filament/Resources/{Tournaments,TournamentCategories}/`,
  `resources/views/components/{tournament-card,tournaments}.blade.php`.
  **Žádná samostatná `/turnaje` listing stránka** — existovala krátce jako dočasná ukázková
  route bez protějšku v `ui/`, ale byla zrušená: Kalendář (viz níže) je jediný hub, kde se
  turnajové karty veřejně vypisují (mimo homepage sekci), duplicitní list stránka vedle něj
  nedávala smysl. **Má teď i detail turnaje** (`/turnaj/{tournament:slug_cs}`, `TournamentController::show()`,
  `resources/views/turnaj.blade.php` + `components/tournament-content.blade.php`) — `ui/`'s
  `turnaj.njk`/`pravidelny-turnaj.njk` jsou (jako dřív `klub.njk`/`herna.njk`) dvě napevno
  ukázkové stránky sdílející `tournamentContent()` widget, ne reálné per-záznamové routování, tak
  řeší stejný `HasSlug` trait jako `Club`/`Herna` (`slugSourceField()` vrací `title`, protože je
  translatable stejně jako `Article`/`Notice`). Nové pole `description` (translatable
  `RichEditor`) je freeform obsah detailu (pravidla/startovné/odkazy), co `ui/`'s mock měl
  natvrdo napsaný v markupu. **`url` zůstává jen externí CTA odkaz** (přihlášky/výsledkový
  servis) vykreslený tlačítkem na detailu — kartička v gridu (`tournament-card`) teď linkuje na
  interní `route('turnaj.show', $tournament)`, ne na `$item->url` (stejný princip jako
  `Club`/`Herna`, kde karta vede na vlastní detail, ne na externí web).
* **Kalendář** (`/kalendar`, `CalendarController`) mirrors `ui/src/kalendar.njk` — hub stránka
  pro turnajový obsah: `Tournament::currentAndUpcoming()` karty + měsíční mini-kalendář +
  `RecurringTournament` postranní karta + statický seznam zdrojových kalendářů. Svaz/Klub/
  Zahraniční checkbox filtr je **záměrně inertní** (stejné řešení jako `/novinky`'s kategorie
  taby — vidět, ale nefiltruje). **Měsíční navigace (prev/next/dnes) je ale skutečná** —
  `?month=Y-m` query param, `CalendarController::buildCalendarMonth()` počítá mřížku dnů +
  eventy ze skutečných `start_date`/`end_date` (stejná logika jako `ui/`'s build-time-only
  `kalendarMesic.js`, jen za běhu a pro libovolný měsíc, ne zamrzlé na jeden) — bráno jako běžná
  navigace (jako skutečná paginace u `/novinky`), ne "filtrování", proto zůstalo funkční i když
  checkbox filtr ne. **`KalendarSettings`** (spatie-settings, stejný vzor jako
  `KlubySettings`/`HernySettings`, `group()` `'kalendar'`) drží editovatelný titulek + podnadpis
  hlavičky (`ManageKalendarSettings`) — bez info panelu (ten `/kluby`/`/herny` mají, Kalendář ne),
  jen `<x-news-header>`'s title/subtitle; oddělené od `HomepageSettings::$tournaments_title`,
  protože jde o jinou stránku s vlastním textem. Drží taky text `<x-recurring-tournaments>`'s
  karty ("Chceš si zahrát?" — `recurring_tag_text`/`recurring_title`/`recurring_text`, stejný
  CZ/EN pár jako ostatní pole) a **`calendar_sources`** — `Filament\Forms\Components\Repeater`
  (`title`/`title_en`/`subtitle`/`subtitle_en`/`url` na řádek), admin-editovatelný seznam pro
  `<x-calendar-sources>`, bez vlastního modelu/tabulky (žije jen na téhle jedné stránce, nic
  jinam neodkazuje ani se odjinud nedotazuje). **Translatable per řádek** — stejný plochý
  `{field}_en` sibling vzor jako ostatní pole téhle třídy, jen opakovaný na každý řádek; `url`
  nepřekládáme (stejný odkaz pro obě jazykové verze). Vědomě **bez `TranslatableTabs` uvnitř
  `Repeater`u** — přepínač jazyka jako samostatný widget vnořený do každého řádku by přidal
  navigační vrstvu navíc bez užitku při tak malé hustotě dat na řádek; obyčejné CZ/EN inputy
  vedle sebe se čtou líp napříč víc řádky. **PAST: `calendar_sources` nesmí
  mít `@var` docblock s generickým typem pole** (`array<int, array<...>>` ani PHPStan-style
  `list<array{...}>`) — `spatie/laravel-settings` z docblocku odvozuje cast a jeho
  `ArraySettingsCast` neumí postavit cast pro vnořené pole-v-poli (buď selže už při parsování
  shape syntaxe, nebo za běhu na `ArraySettingsCast::__construct(null)`). Bez `@var` vůbec
  (jen obyčejná PHP `array` typová nápověda) reflection cast building úplně přeskočí (viz
  `Spatie\LaravelSettings\Support\PropertyReflector`) — přesně to, co chceme, žádné
  per-položkové castování není potřeba.
  **`RecurringTournament`** (pravidelné amatérské turnaje, např. "Turnaje v Balabušce, každá
  středa") je **samostatný model**, ne flag na `Tournament` — different shape (`frequency` text
  místo `start_date`/`end_date`, žádná kategorie/badge, malý stabilní počet záznamů) by na
  `Tournament` znamenalo hromadu polí relevantních jen pro jeden typ záznamu a ztrácely by se
  admin-side v rostoucí tabulce turnajů. Řeší stejný `HasSlug` trait (`slugSourceField()` →
  `title`) a **vlastní detail** (`/pravidelny-turnaj/{recurringTournament:slug_cs}`,
  `RecurringTournamentController::show()`, `resources/views/pravidelny-turnaj.blade.php`) —
  znovupoužívá `<x-tournament-content>` (ta na `Tournament` modelu vůbec nezávisí), jen s pevnou
  klasifikací `tagText="Amatérský turnaj"`/`tagColor="gold"` (na rozdíl od `Tournament`, `Recur-
  ringTournament` nemá vlastní kategorie). **Nepovinná vazba `herna_id` (`belongsTo Herna`)** —
  když je vyplněná, detail zobrazí kartu "Odkazy" s proklikem na `route('herna.show', ...)`
  (mapa/adresa řeší už hotová herna detail stránka, tahle vazba slouží jen k prokliku). `app/
  Models/RecurringTournament.php`, `database/seeders/RecurringTournamentSeeder.php`, `app/
  Filament/Resources/RecurringTournaments/`. Nové sdílené komponenty `calendar-month`,
  `recurring-tournaments`, `calendar-sources`, `no-results` (`resources/views/components/`)
  mirrors stejnojmenné `ui/`'s macra 1:1.
* **Soutěže** (`/souteze`, `SoutezeController`) mirrors `ui/src/souteze.njk` — vědomě jen
  **"hub" stránka s přehledovými informacemi**, ne finální podoba (uživatel počítá s tím, že se
  jednotlivé soutěže/série časem rozpadnou na samostatné stránky). `<x-page-hero>` (titulek/
  podnadpis/text/až 4 staty) + `<x-jump-nav>` (pilulky nahoře, scroll-spy JS už existuje v
  `app.js` — kopíroval se 1:1 s celým `main.js` dávno předtím, byl jen neaktivní no-op bez
  odpovídajícího markupu) + řada číslovaných `<x-content-section>` bloků + `<x-leaderboards>`
  bez `max-entries` (plný TOP 10 žebříček, na rozdíl od homepage teaseru).
  **`CompetitionSection`** — nový model pro číslované sekce (Regiony, Česká poolová tour, MČR
  jednotlivců...), schválně **ne** `Competition` (až vznikne skutečná entita jedné soutěže/
  série s vlastní stránkou, ať jí jméno nekoliduje). `title`/`eyebrow`/`nav_label`/`body`/
  `aside`/`below` (translatable), `anchor` (**ne** `HasSlug` — je to ručně zadaná `#kotva` pro
  jump-nav, často zkratka odlišná od titulku, ne mechanický slug z názvu), `sort_order`. Číslo
  sekce (1, 2, 3...) se **nikde neukládá** — je to jen pozice ve `@foreach` smyčce
  (`resources/views/souteze.blade.php`), stejná "odvozená hodnota místo ručně drženého pole"
  logika jako `Tournament::soon()`/`dateText()`. Vlastní Filament Resource (jako Article/Notice),
  ne Settings stránka — je to opakující se, řaditelná entita, ne stránková hlavička.
  **Vědomá ztráta vizuální věrnosti u `aside`/`below`:** `ui/`'s mock má tam ručně psaný markup
  (dvousloupcový stat rozpad, mřížka kategorie-dlaždic, barevná `infoPanel()` karta s tagem a
  tlačítkem) — RichEditor tohle nedokáže reprodukovat (umí jen odstavce/tučně/seznamy/odkazy).
  Místo budování strukturovaných polí pro každý bespoke prvek je `aside`/`below` obyčejný
  RichEditor obsah zabalený do obecné `.c-section__card` krabičky — schválené zjednodušení
  (viz konverzace), ne přehlédnutí, protože se stejně čeká na přestavbu podle bodu výše.
  **`<x-content-section>`** má na rozdíl od `ui/`'s `contentSection()` makra (jen jeden
  `caller()` blok) skutečné pojmenované sloty `aside`/`below` (Blade to umí, Nunjucks ne) —
  žádný `{% set %}`-string-workaround navíc. `eyebrowColor` param z `ui/`'s makra se **neportoval**
  — žádná stránka nikdy nepoužije `"accent"` a `.c-section__eyebrow--accent` modifier CSS
  neexistuje ani v `ui/`, ani ve `web/`, takže to nebyla reálná funkční možnost.
  **`SoutezeSettings`** (spatie-settings, stejný vzor jako `KalendarSettings`) drží jen
  `<x-page-hero>`'s obsah (title/subtitle/text + `stats` repeater `{value, label, label_en}`,
  opět bez `@var` docblocku ze stejného důvodu jako `KalendarSettings::$calendar_sources`).
  `app/Models/CompetitionSection.php`, `database/seeders/CompetitionSectionSeeder.php`, `app/
  Filament/Resources/CompetitionSections/`, `app/Settings/SoutezeSettings.php`, `app/Filament/
  Pages/ManageSoutezeSettings.php`, nové komponenty `page-hero`/`jump-nav`/`content-section`.
* **Pravidla** (`/pravidla`, `PravidlaController`) mirrors `ui/src/pravidla.njk` — `<x-page-hero>`
  (bez statů) + mýtus/fakt akordeon + grid `<x-rule-card>` karet pro jednotlivé disciplíny.
  **`<x-myth-faq>`** znovupoužívá `.c-faq`'s CSS 1:1 (question/answer/chevron/panel + volitelný
  `.c-faq__badge` prvek) — žádné nové styly, jen jiný obsah badge ("Mýtus"/"Správně" místo
  číslovaného indexu) a první položka pre-opened (`aria-expanded="true"`) — `resources/js/app.js`
  už tohle přesně řešilo (komentář u `[data-faq-toggle]` handleru zmiňuje `mythFaq`'s první
  položku), i když se tenhle Blade port teprve teď napsal. **`RuleCard`** — vlastní model
  (title/subtitle/text/icon/image/button_url/sort_order), ne Settings repeater jako
  `calendar_sources`/`stats` — má `FileUpload` obrázek a je to samostatně řaditelná entita, ne
  page-singleton text, takže sedí lépe do vzoru `Partner`/`Banner` (vlastní Filament Resource,
  seed obrázky přes `database/seeders/assets/rule-cards/` → `Storage::disk('public')`, stejně
  jako `PartnerSeeder`). `iconVariant="balls"` z `ui/`'s makra se **neportoval** (stejný důvod
  jako `content-section`'s zahozený `eyebrowColor="accent"` — žádná karta ho nepoužívá) — místo
  uloženého příznaku se varianta (obrázek vs. ikona) odvozuje z toho, co je vyplněné.
  **`PravidlaSettings`** drží jen hlavičkové texty (hero + oba `<h2>` nadpisy sekcí) — žádný
  repeater. **`Myth`** (mýty/fakta) začínal jako `PravidlaSettings`'s repeater (stejný vzor jako
  `calendar_sources`/`stats`), ale povýšil se na vlastní model + Filament Resource
  (`myth_text`/`correct_text`, `TranslatableTabs::make()` — jeden přepínač jazyka na záznam,
  stejně jako u `RuleCard`/`CompetitionSection`) — zatím se vypisují jen na `/pravidla`, ale
  počítá se s tím, že by časem mohly být i jinde, a tomu repeater vázaný na jednu stránku
  neodpovídá. **Vědomě zatím bez vlastní taxonomie/skupiny** (na rozdíl od `FaqGroup`/`FaqItem`,
  kde `belongsToMany` řeší umístění na víc místech) — až se reálně objeví druhé místo, kde se
  mýty mají vypisovat, přidá se stejný `belongsToMany` vzor, ne dřív (viz bod 4 "Uzavřený/
  neměnný seznam..." — tady je to spíš "zatím jen jedno umístění, neřeš to předem").
* **Jak začít** (`/jak-zacit`, `JakZacitController`) mirrors `ui/src/jak-zacit.njk` — `<x-page-hero>`
  (bez statů) + 4 `<x-feature-card>` quick-select dlaždice + 3 `<x-content-section>` bloky (jeden
  na "cestu": Úplný začátečník/Rekreační hráč/Rodič) + `<x-form-section>` s `<x-match-form>` +
  napevno psaná uzavírací CTA sekce. Nové komponenty `feature-card`/`steps`/`match-form`/
  `form-section` (`resources/views/components/`) mirrors stejnojmenná `ui/`'s makra/widget 1:1.
  **`JakZacitSection`** — vlastní model pro každou "cestu" (na rozdíl od `CompetitionSection`u,
  který svůj `aside`/`below` zjednodušuje na freeform RichEditor, tahle stránka měla explicitně
  zůstat vizuálně věrná šabloně) — `steps` je JSON repeater (`icon`/`title`/`text`, plochý
  `_en` sourozenec na pole, stejná konvence jako `KalendarSettings::$calendar_sources`, žádné
  `TranslatableTabs` uvnitř Repeateru), `aside_panel_*`/`aside_card_*` jsou strukturovaná pole
  (ne rich-text karta) — replikují `infoPanel()` (tmavá karta, `aside_panel_button_text`/`_url`
  nepovinné — sekce "Rodič" panel bez tlačítka nemá) a druhou menší kartu (`aside_card_*`,
  tlačítko vždy vyplněné). **FAQ per sekce znovupoužívá `FaqItem`/`FaqGroup`** — žádné nové pole
  na modelu, `JakZacitSection::faqItems()` dotazuje `FaqGroup` se `slug` shodným s `anchor`
  (`FaqGroupSeeder`'s `zacatecnik`/`rekreacni-hrac`/`rodic` skupiny, `JakZacitFaqItemSeeder`).
  Sdílené otázky s odlišnou odpovědí napříč skupinami (např. "Jak probíhá registrace?" u
  Rekreačního hráče i Rodiče) si vynutily vlastní matching logiku v seederu místo
  `updateOrCreateByTranslation('question', ...)` — ten matchuje globálně podle textu otázky, což
  by dvě různé odpovědi se stejným zněním otázky zkolabovalo do jednoho sdíleného řádku (viz
  `JakZacitFaqItemSeeder`'s docblock). **`JakZacitSettings`** (stejný vzor jako
  `SoutezeSettings`) drží hero titulek/text, `feature_cards` repeater (4 dlaždice, `link_url`
  neschválně 1:1 navázané na sekce — dvě dlaždice v `ui/`'s mocku cílí na stejnou kotvu
  `#rekreacni-hrac`), a texty uzavírací CTA sekce + hlavičky formuláře poptávky. Tlačítka
  uzavírací CTA sekce (Najít klub/hernu/turnaj) zůstávají napevno v Blade (strukturální
  navigace na existující stránky přes `route()`, stejný princip jako `GeneralSettings::$cmbs_tv_url`'s
  hardcoded věta).
* **Sportovní svaz** (`/sportovni-svaz`, `SvazController`) mirrors `ui/src/sportovni-svaz.njk` —
  `<x-page-hero>` (bez statů) + dvousloupcová info sekce (ČMBS text/odkazy/"vyřídit" odkazy +
  logo | Výkonný výbor) + `<x-documents>` (archiv dokumentů podle roku) + `<x-hp-notices>`
  (znovupoužitá homepage komponenta, žádná nová). Nové komponenty `committee-list`/`documents`
  (`resources/views/components/`) mirrors stejnojmenná `ui/`'s makro/widget 1:1 — JS chování
  (roky jako taby, kategorie jako `.c-faq` akordeon) bylo už dřív zkopírované do `app.js` beze
  změny (`data-doc-tabs`/`data-doc-tab`/`data-doc-panel`).
  **`CommitteeMember`** — vlastní model (name/role/email/photo/sort_order), stejný vzor jako
  `Partner` (`role` translatable — editorial text — na rozdíl od `name`/`email`, které jsou
  vlastní jména/kontaktní údaje).
  **Archiv dokumentů je reálný, ne statický mock** — `DocumentCategory` (admin-manageable
  taxonomie jako `ArticleCategory`, ne enum, protože svaz může časem přidat novou kategorii) +
  `Document` (`document_category_id` `belongsTo`, `year` nullable — `null` = evergreen dokument
  bez vazby na sezónu, ne chybějící údaj — `name`, skutečný `FileUpload`). `meta_text`
  ("PDF · 850 KB") je computed z reálného nahraného souboru (`Number::fileSize()` + přípona), ne
  ručně psané pole jako v `ui/`'s mocku — stejný princip jako `Tournament::dateText()`.
  `SvazController::buildDocumentYears()` sestavuje `<x-documents>`'s `years` pole za běhu:
  jeden panel na každý reálně existující rok (sestupně) + jeden trailing panel "Obecné" pro
  dokumenty bez roku, kategorie/roky bez dokumentů se v přehledu vůbec nezobrazí. **Seed dat je
  vědomě jen za aktuální sezónu (2026) + evergreen dokumenty**, ne celých 13 mock-rok let z
  `ui/src/_data/svazDokumenty.js` (ten fejkuje stejné 4 soubory pro každý rok 2014–2026 jen aby
  bylo co ukázat v každé záložce) — zpětné vyplnění 13 let placeholder PDF by byl jen seed šum,
  reálné minulé roky nezpětně nezískají reálné soubory stejně; další roky přibydou přes admin,
  jak která sezóna skončí (viz `DocumentSeeder`'s docblock). **`SvazSettings`** (stejný vzor jako
  `SoutezeSettings`) drží hero, info sloupec (text + 2 externí odkazy + "Potřebuji vyřídit …"
  repeater), a titulky sekcí dokumentů/zpráv. **`committee_text`** (obyčejný RichEditor, ne
  zvlášť pole pro e-mail a zvlášť pro číslo účtu) — první verze měla `committee_email`/
  `committee_iban` jako dvě samostatná pole s větou napevno v Blade (stejný princip jako
  `GeneralSettings::$cmbs_tv_url`), ale to rozdělení nebylo strukturální, jen to, jak `ui/`'s
  mock text napsal (dva odstavce vedle sebe) — administrátor chtěl psát obojí (e-mail i číslo
  účtu jako vlastní odstavec) přímo, ne přes dvě uzavřená pole. Logo ČMBS je statický brand
  asset (`public/uploads/cmbs-logo.png`), ne DB pole — stejný princip jako header/patička logo.
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
  jsou PHP backed enumy v `app/Enums/`, ne DB taxonomie — viz bod 4. **Má teď i veřejné stránky**
  (`/kluby` + `/klub/{club:slug_cs}`, `/herny` + `/herna/{herna:slug_cs}`, vč. self-hosted
  Leaflet mapy) — viz sekce 3 "Kluby / Herny". **Veřejný registrační formulář (`/registrace-herny`)
  je teď taky hotový** — viz sekce 3 "Registrace herny".
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

První admin uživatel: `php artisan make:filament-user`. Další se přidávají přímo v adminu (`Systém > Uživatelé`, `App\Filament\Resources\Users\UserResource`) — žádné role zatím, kdo se přihlásí má plný přístup; jediná ochrana je, že si uživatel nemůže smazat vlastní účet (`UsersTable`/`EditUser` skrývají delete akci pro `auth()->user()`).

DB je zatím SQLite (`database/database.sqlite`) — žádná závislost na běžícím MySQL serveru. Přepnutí na MySQL (až bude potřeba): upravit `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` v `.env` a spustit `php artisan migrate:fresh`.

> ⚠️ **PAST: `DatabaseSeeder`/libovolný seeder nesmí mít `use WithoutModelEvents;`, pokud se v projektu spoléháme na model eventy (např. `HasSlug`'s `creating`).** Laravel to do `DatabaseSeeder` scaffoldu dává defaultně (kvůli rychlosti u `User::factory(10)->create()`), ale ten trait **potichu vypne všechny model eventy pro celý běh seederu** — `slug` se pak nikdy nedopočítá a insert spadne na `NOT NULL constraint` bez zjevné souvislosti s eventy. Trait jsme z `DatabaseSeeder` odstranili — nepřidávej ho zpátky, pokud si nejsi jistý, že žádný aktivní model nespoléhá na `creating`/`saving`/atd.

> ⚠️ **PAST: `php artisan serve` defaultně bindí/vypisuje `127.0.0.1`, i když `APP_URL` je `http://localhost:8000`** (host defaultuje na `Env::get('SERVER_HOST', '127.0.0.1')` — nezávisle na `APP_URL`). Prohlížeč bere `127.0.0.1` a `localhost` jako **různé originy** navzdory stejnému stroji/portu — pokud se v adminu otevře stránka na jednom z nich, ale `Storage::disk('public')->url(...)` (podle `APP_URL`) generuje URL obrázků na tom druhém, Livewire's `fetch()` dotaz (např. na velikost souboru u `FileUpload` náhledu) narazí na skutečné CORS zamítnutí. Oprava: `SERVER_HOST=localhost` v `.env`/`.env.example`, ať `php artisan serve` vypisuje/bindí stejný host jako `APP_URL`.

> ⚠️ **PAST: `php artisan serve`/`composer run dev` bez `PHP_CLI_SERVER_WORKERS` běží jednovláknově — obsluhuje jen jeden request najednou.** Livewire (Filamentu) si pro leccos (např. náhled velikosti nahraného souboru u `FileUpload`) posílá vlastní asynchronní dotaz na server — pokud v tu chvíli visí jiné spojení, tenhle dotaz čeká ve frontě donekonečna a v UI to vypadá jako věčné "Loading" bez chybové hlášky. `PHP_CLI_SERVER_WORKERS=4` v `.env` (odkomentováno v `.env`/`.env.example`) **samo o sobě nestačí** — `serve` defaultně běží v `--reload` režimu (auto-restart při změně souboru), který je s víc workery neslučitelný, takže Laravel v tichosti spadne zpátky na 1 worker (jen s WARN hláškou v konzoli serveru, snadno přehlédnutelnou). Potřeba i `--no-reload` — jelikož `composer run dev`/`php artisan dev` interně vždycky spouští holé `php artisan serve` bez možnosti předat vlastní flagy, řešení je registrace vlastního `server` příkazu s vyšší prioritou v `AppServiceProvider::boot()`: `DevCommands::artisan('serve --no-reload', 'server')` (userland volání přebije defaultní `registerDefaults()`'s stejnojmenný příkaz — ověř přes `php artisan dev:list`, že `server` řádek skutečně obsahuje `--no-reload`).

---

## 3. Workflow: přenos z `ui/` do `web/`

Viz i pravidlo v hlavním `README.md`. `ui/` je zdroj pravdy pro vzhled — v `web/` se nikdy nedělá vizuální změna jako první.

### Postup portování jedné komponenty (makro → Blade komponenta)

1. Otevři hotové/odladěné makro v `ui/src/_includes/macros/<name>.njk` a jeho CSS v `ui/src/styles/02_components/<name>.css` (nebo `section/<name>.css` u `.c-section--*` variant).
2. Vytvoř Blade protějšek v `web/resources/views/components/<name>.blade.php`. Parametry makra (`{% macro foo(a, b="x") %}`) → `@props(['a', 'b' => 'x'])`.
3. **BEM třídy (`.c-*`, `.u-*`, `.t-*`) se přenášejí beze změny** — stejný název třídy v Blade i Nunjucks, ať CSS soubor (bod 4) sedí na obě strany beze změny.
4. **Celé `ui/src/styles/` je od teď zkopírované 1:1 do `web/resources/css/`** (i soubory bez Blade protějšku zatím) — `app.css`/`02_components/card.css`/`02_components/section.css` mají kompletní `@import` seznam, ve stejném pořadí jako `ui/`'s `style.css`/`card.css`/`section.css`. Pokud v `ui/` přibude úplně nový CSS soubor, zkopíruj ho a přidej `@import` na odpovídající místo (podle pořadí v `ui/`'s zdrojovém souboru) — jinak nic navíc dělat nemusíš, CSS už tam je.
5. Ověř, že třídy, co komponenta používá, v zkopírovaném CSS existují (viz krok 4) — pokud něco vizuálně nesedí, je jistota, že to není "chybějící CSS", takže hledej rovnou chybu v Blade markupu/třídách.
6. Pokud makro obsahuje `{% icon %}` shortcode (Heroicons), použij Blade ekvivalent — `blade-ui-kit/blade-heroicons` je už závislost Filamentu. **Projekt preferuje "mini" (`m-`) geometrii i pro větší ikony** (stejná konvence jako `ui/`, viz `skills/ui-component-guide.md` Pravidlo 7): `<x-heroicon-m-chevron-down width="20" height="20" class="..." />`. Když je název ikony proměnná (např. `button()`'s `icon`/`leadingIcon` parametr), použij `<x-dynamic-component :component="'heroicon-m-'.$icon" :width="$iconSize" :height="$iconSize" />` — viz `components/button.blade.php`. Pro `{% brandIcon %}` (Simple Icons) použij `<x-brand-facebook>` atd. — viz "Brand ikonky" níže.
7. Ověř vizuálně vedle sebe (`ui/` dev server vs. `web/` dev server) — musí sedět 1:1.

**Design tokeny** (barvy, radius, fonty, stíny) jsou v `web/resources/css/app.css` uvnitř `@theme { ... }` — je to ruční zrcadlo `@theme` bloku z `ui/src/styles/style.css`. Změníš-li token v `ui/`, proveď stejnou změnu i tady (obě místa výslovně na sebe odkazují komentářem).

**`extraClass` parametr maker se do Blade nepřenáší jako samostatný prop** — Blade má nativní mechanismus přesně pro tohle (`$attributes`), použij ho: komponenta v základu vrací `{{ $attributes->merge(['class' => $classes]) }}` na root elementu, volající pak přidá extra třídy prostě jako `class="..."` atribut na tag komponenty (`<x-tag ... class="mb-2" />`), stejně jako `id`, `data-*` apod. Viz `components/tag.blade.php`, `components/button.blade.php`.

**Rich-text pole** (makro používalo `| safe` filtr na hodnotu z dat, např. FAQ odpověď): v DB obyčejný `text` sloupec s uloženým HTML, ve Filament formuláři `RichEditor::make(...)` (ne `Textarea`/`TextInput` — administrátor needituje HTML ručně), v Blade vypsat neescapovaně přes `{!! $value !!}` (Blade ekvivalent `| safe`). Viz `FaqItemForm` + `components/faq.blade.php`.

**JS chování widgetu**: `web/resources/js/app.js` je od teď kompletní kopie `ui/src/js/main.js` (ne jen porty s Blade protějškem) — bloky bez odpovídajícího markupu v `web/` (jump-nav, document-tabs, GLightbox, Leaflet mapa) jsou neškodné no-opy, protože nejdřív `querySelector`ují svůj element/knihovnu a bez nich nic nedělají. Když přibude nový blok v `ui/`'s `main.js`, zkopíruj ho stejně 1:1; není potřeba čekat, až bude mít Blade stránka hotová.

**Brand ikonky (Simple Icons)** — žádný oficiální Composer/Blade balíček neexistuje, takže těch pár, co potřebujeme (facebook/instagram/whatsapp/youtube v headeru a patičce), je committnutých natvrdo jako obyčejná SVG v `web/resources/svg/brand/` (zrcadlí `ui/.eleventy.js`'s `brandIcon()` shortcode: `<title>` pryč, `fill="currentColor"` napečené do souboru — zdroj `ui/node_modules/simple-icons/icons/<name>.svg`). Použití: `<x-brand-facebook width="18" height="18" />`. **Nová sada musí být zaregistrovaná v `config/blade-icons.php`'s `sets`, ne v service provideru přes `Factory::add()` v `boot()`** — `Factory`/`IconsManifest` jsou singletony, které si seznam ikon k Blade komponentám zapamatují (memoizují) při prvním resolvnutí view factory, což se stane už během `register()` fáze jiných providerů, dávno před tím, než doběhne `boot()` naší vlastní `AppServiceProvider` (ověřeno — přidání setu v `boot()` tiše nefungovalo, `config/blade-icons.php` ano). Přidání další ikony: zkopírovat SVG (title pryč, `fill="currentColor"` na `<svg>`) do `resources/svg/brand/`, hotovo — soubor se objeví automaticky, žádná další registrace není potřeba.

### Site chrome (layout, header, hlavní menu, footer, newsletter)

- `<x-layouts.app title="..." >...</x-layouts.app>` (`resources/views/components/layouts/app.blade.php`) je Blade **layout komponenta** (ne `@extends`/`@section`) obalující každou veřejnou stránku — zrcadlí `ui/src/_includes/layouts/base.njk` (`<html>`/`<head>`/`@vite`) + `<div class="c-page-wrapper">` + header/footer, které si `ui/` opakuje v každé stránce zvlášť. Zatím bez `hasGallery`/`hasMap` vendor asset pipeline (GLightbox/Leaflet nejsou portované) — propy existují jen pro budoucí parity, negatují nic.
- `layouts.header`/`layouts.footer` (`resources/views/layouts/{header,footer}.blade.php`) jsou obyčejné **view partiály** (přes `@include`, ne komponenty s propy) — zrcadlí `ui/src/_includes/layouts/{header,footer}.njk` 1:1, včetně `navItems` pole natvrdo v `@php` bloku (stejně jako `ui/`'s `{% set navItems = [...] %}`).
- `ui/`'s lokální Nunjucks makra uvnitř `header.njk` (`navDropdown`, `mobileNavItem`) nemají v Blade ekvivalent "makro v rámci jednoho souboru" — staly se z nich samostatné komponenty `components/nav-dropdown.blade.php` a `components/mobile-nav-item.blade.php`. `mobile-nav-item` už obsahuje starší UI opravu (text je vždy skutečný odkaz, jen šipka je toggle) — needituj to zpátky na "celý řádek = toggle".
- Statické brand assety bez DB záznamu (logo v headeru/patičce) patří do `public/uploads/` přímo (`web/public/uploads/cesky_pool.png`) — **ne** přes `Storage::disk('public')` seeder-assets vzor (ten je pro DB-vázaný obsah, viz `PartnerSeeder`). Cesta `/uploads/...` v Blade je stejná jako v `ui/`.
- `newsletter()` → `<x-newsletter />` (`components/newsletter.blade.php`), stejné propy jako makro.
- **Světlá/tmavá hlavička** (`.c-header` vs. `.c-header.t-dark`, viz `ui/`'s `demo-dark-header.njk`'s `{% set headerDark = true %}`) — `<x-layouts.app>`'s `headerDark` prop teď defaultuje na `GeneralSettings::$header_dark` (sitewide toggle v adminu, "Obecné nastavení" → sekce "Vzhled"; světlá = výchozí/`false`), místo aby byl navěky `false`. Stránka může globální volbu přebít explicitním `:header-dark="true/false"` na `<x-layouts.app>`, ale žádná to dnes nedělá — je to čistě jeden sitewide přepínač, ne per-stránkové nastavení.
- Ověření: `tests/Feature/PublicPagesTest.php` kontroluje, že veřejné stránky vrací 200 **a** obsahují chrome markery (`c-header__nav`, `c-footer__nav`) — 200 samo o sobě neodhalí zapomenutý `<x-layouts.app>` wrapper. Přepínač hlavičky má vlastní test (`test_header_dark_variant_follows_general_settings_toggle`).

### Novinky / Článek (`/novinky`, `/novinky/{article:slug_cs}`)

Referenční příklad prvního **reálného list+detail páru** (na rozdíl od `/partneri`/`/faq`/`/turnaje`, což jsou pořád jen preview stránky bez plného obsahu okolo).

- **Skutečná paginace, ne dekorativní.** `ui/`'s `macros/pagination.njk` jen předstírá `?page=N` odkazy nad statickým mockem (`currentPage`/`totalPages` natvrdo). My máme reálná data → `Article::paginate(9)` + `components/pagination.blade.php` čte skutečný `LengthAwarePaginator` (`$paginator->url($page)`, `->hasMorePages()` atd.) — stejné BEM třídy/markup jako `ui/`, takže žádná CSS změna, ale funkčně je to o level líp než předloha.
- **"Podobné články" jsou reálný dotaz, ne ručně vybraná trojice** jako v `ui/`'s statickém příkladu — `ArticleController::show()` bere 3 nejnovější články ze **stejné kategorie** (fallback na nejnovější celkově, když článek nemá kategorii). U článku, kde žádný jiný v kategorii není, se sekce korektně vůbec nezobrazí (`@if ($related->isNotEmpty())`).
- **Kategorie filtr taby jsou pořád dekorativní** (stejně jako v `ui/` — tam explicitně říká "search/filter je záměrně inertní"), ale postavené z reálných `ArticleCategory` záznamů + "Vše" natvrdo napřed, ne z hardcoded pole. `data-category` hodnota je `Str::slug($category->name)` za běhu — `ArticleCategory` nemá vlastní `slug` sloupec, není potřeba, dokud se filtr fakticky nezapojí.
- **Galerie zatím bez lightboxu** (GLightbox není portovaný) — dlaždice v `components/gallery.blade.php` mají navíc `target="_blank"` oproti `ui/`'s předloze, ať klik aspoň neopustí článek. Až se GLightbox portuje, `target="_blank"` zase odstranit a přidat `glightbox`/`data-gallery` zpátky.
- Nové utility soubory poprvé portované touhle dávkou: `04_utils/{spacing,backgrounds,borders}.css` (`.pt-none`/`.pb-none`, `.bg-gradient-light`, `.border-top`/`.border-bottom`) — potřebné, jakmile se sekce s různým/stejným pozadím řadí za sebe (viz `skills/ui-component-guide.md`'s "PAST" o dvojitém paddingu/borderu).
- **"Důležité zprávy" sidebar je hotový** — `.c-section__grid c-news-grid__layout` (dvousloupcový grid) + `<aside class="c-news-grid__sidebar">` s `notice-card`y, přesná struktura `widgets/news-grid.njk`'s `{% call %}` bloku. `ArticleController::index()` bere 4 nejnovější zprávy (`Notice::orderByDesc('published_at')->take(4)`) místo `ui/`'s 4 ručně vypsaných příkladů — stejný princip jako u "podobných článků".

### Zprávy výboru (`/zpravodajstvi/vykonny-vybor`, `/zpravodajstvi/vykonny-vybor/{notice:slug_cs}`)

- `components/notice-card.blade.php` mirrors `macros/card/notice.njk` (`size="md"` kompaktní řádek se šipkou | `size="lg"` větší karta s excerptem, bez šipky — na listing stránce používáme `size="lg"`, stejně jako `ui/`).
- **Detail stránka nemá vlastní komponentu** — `ui/`'s `vykonny-vybor-detail.njk` přímo znovupoužívá `articleContent()` (jen `tagPosition="inline"`, `tagColor="accent"`), takže `zpravodajstvi/vykonny-vybor-detail.blade.php` dělá to samé s `<x-article-content>` — žádný nový "notice content" widget.
- "Důležité" (`is_important` boolean) se mapuje na `tagText="DŮLEŽITÉ"`/`"Důležité"` + `tagColor="accent"` (default barva `notice-card`u i `<x-tag>` volání) — stejný mechanismus jako v `ui/` (žádná speciální `.c-notice--important` třída, jen barevný tag).
- **"Kontakt" info box je hotový** — stejný `.c-section__grid c-news-grid__layout`/`<aside class="c-news-grid__sidebar">` layout jako Novinky, ale naplněný `.c-news-grid__info` blokem (nadpis s obálkovou ikonou + `mailto:` odkaz), ne notice kartami — přesně jak to dělá `ui/`'s vlastní `newsGrid()` volání na téhle stránce.

### Kluby / Herny (`/kluby`, `/klub/{club:slug_cs}`, `/herny`, `/herna/{herna:slug_cs}`)

CSS pro tuhle dvojici (`02_components/section/{kluby,club-hero,club-detail,club-directory,herna-list,herna-detail}.css`) bylo 1:1 portované už dřív, spolu s `map-card.css`/`alert.css` — teprve Blade/PHP strana byla samostatný krok.

- **Leaflet mapa je skutečná knihovna, ne statický obrázek** — `ui/` ji vendoruje jako Eleventy passthrough-copy (`node_modules/leaflet/dist/*` → `/css|js/vendor/leaflet.*`, globální `window.L`, načtený jen na stránkách s `hasMap: true`). `web/` používá Vite (jeden globální bundle pro celý web, stejně jako u všeho ostatního v `resources/js/app.js` — žádné per-stránkové entry pointy), takže port je `npm install leaflet` + `import * as L from 'leaflet'; import 'leaflet/dist/leaflet.css'; window.L = L;` na začátku `app.js` — zbytek `[data-club-map]` handleru (custom `.c-map-pin` divIcon, `L.map()`/`L.marker()`/`L.tileLayer()`) je **beze změny zkopírovaný** z `ui/src/js/main.js`, protože ten kód už na `window.L` čekal (viz komentář v souboru před portem). Mapa se teď natahuje na každé stránce (malá cena za jeden bundle), ne jen na `hasMap` stránkách jako v `ui/`.
  - **PAST: `lat`/`lng` musí být explicitně `(float)` cast před `json_encode()` pro `data-club-map`.** `Club`/`Herna` mají `'lat' => 'decimal:7'` (kvůli přesnosti), což PHP/Eloquent serializuje jako **string** (`"50.0836000"`), ne number — `json_encode` by pak poslal `"lat": "50.0836000"` (v uvozovkách). `main.js`'s `typeof club.lat === 'number'` filtr by takové záznamy tiše vyřadil (žádná chyba, jen chybějící piny na mapě). Řešení: `'lat' => (float) $club->lat` při stavbě pole pro `json_encode`, viz `resources/views/kluby.blade.php`/`components/map-card.blade.php`.
  - `data-pin-color="primary"` na Herny mapách (modré piny) vs. výchozí červená na Kluby — stejná `main.js` konvence, jen předaná dál přes `pin-color` prop na `<x-map-card>`.
- **`region` je `App\Enums\Region` backed enum na modelu** (`'region' => Region::class` cast) — `Collection::groupBy('region')` na `ClubController::index()` proto **musí** seskupovat přes `fn ($club) => $club->region?->value`, ne přímo `'region'` stringem — seskupení přes samotnou enum instanci jako klíč kolekce by spadlo (`Illegal offset type`, protože PHP/Illuminate klíče kolekce/pole musí být `int|string`). Stejně tak kdekoliv se `$region` vypisuje v Blade (`<option value="{{ $region }}">`), musí to být `$region->value`/`->getLabel()` — enum instance samo o sobě nemá `__toString()`.
- **`x-alert` a `x-map-card`** jsou nové sdílené komponenty (dřív existovalo jen `alert.css`/`map-card.css`, ne Blade strana) — `x-alert` zobrazuje `Club::recruitmentMessage()` accessor (**už existoval** z i18n práce, žádná nová logika), `x-map-card` bere pole záznamů (`items`, vždy přesně 1 položka tady) ve stejném tvaru, jaký potřebuje `[data-club-map]`.
- **`Str::initials()` macro** (`AppServiceProvider::register()`) — zrcadlí `ui/`'s Eleventy `initials` filtr (badge na herna kartách, např. "Billiard Club Harlequin Praha" → "BCH").
- **Fotogalerie u herny stejná provizorka jako `/novinky`** — GLightbox není portovaný, `target="_blank"` místo lightboxu (viz `components/gallery.blade.php`'s stejný komentář).
- **Search input a kraj `<select>`** na obou list stránkách jsou **dekorativní** (`data-herna-search`/`data-herna-region`, nic je nezpracovává) — stejný princip jako kategorie-filtr taby na `/novinky`, skutečné filtrování je samostatné budoucí rozhodnutí.
- Klubová `region` seskupovací tabulka (`club-directory`) řadí regiony/kluby abecedně (`Club::orderBy('region')->orderBy('name')`) — `ui/`'s mock měl regiony v ručně napsaném pořadí (Praha první), což nebylo záměrné rozhodnutí, jen pořadí psaní mock dat, takže abecední řazení je v pořádku.
- **PAST: `about_text` (RichEditor, tedy HTML) se musí vypisovat přes `{!! !!}`, ne `{{ }}`** — první verze `club-detail.blade.php`/`herna-detail.blade.php` to měla escapované (zděděno z `ui/`'s mock dat, kde `aboutText` byl čistý text, takže `{{ }}` tam bylo správně) — na stránce se pak doslova zobrazovalo `<p>text</p>`. Stejný vzor jako `components/article-content.blade.php`/`banner.blade.php`. Bez obalujícího `<p>`/`<div class="...">{{ }}</p>` navíc — RichEditor obsah si vlastní blokové tagy nese sám, obalení by vytvořilo vnořené `<p><p>...`.
- **`KlubySettings`/`HernySettings`** (dvě samostatné spatie-settings třídy, ne jedna kombinovaná — `/kluby` a `/herny` jsou dvě různé stránky, i když si jsou obsahově hodně podobné, takže mají i oddělenou správu obsahu/admin stránku, stejný `{field}_en` CZ/EN vzor jako `HomepageSettings`, `group()` `'kluby'`/`'herny'`, admin `ManageKlubySettings`/`ManageHernySettings` ve skupině "Stránky") — editovatelný hlavní titulek + info panel (štítek/titulek/text/tlačítko) pro danou listing stránku. **Vědomě mimo scope:** pevné UI popisky sdílené napříč komponentami (`"Zaregistrovat nový klub"`, `"Seznam klubů"`, `"Vyhledat hernu"`, `"Kraj"` — natvrdo v `club-directory.blade.php`/`herna-list.blade.php`) a info panelu 3 řádky výhod (ikona+text) — obojí je buď obecná UI/lokalizační vrstva (budoucí sitewide řešení, ne per-stránkové nastavení), nebo "strukturální design copy" stejného typu jako `GeneralSettings::$cmbs_tv_url`'s hardcoded věta v `components/tournaments.blade.php`.

### Registrace herny (`/registrace-herny`)

Mirrors `ui/src/registrace-herny.njk` — první veřejný **zápisový** formulář v projektu (dřív jen
čtecí/obsahové stránky), žádný Filament ani admin-auth na téhle straně.

- **Rovnou do `Herna` s `status = HernaStatus::Pending`, ne zvlášť staging tabulka** —
  `HernaStatus` enum a `HernaController::index()`'s `where('status', Approved)` filtr už na tenhle
  workflow byly připravené (viz `Herna`/`HernaStatus`'s docblocky), jen na ně nic nezapisovalo.
  Schválení je pak jen změna jednoho sloupce na existujícím záznamu (viz níže), ne kopírování
  polí ze staging tabulky do reálné. **`HernaController::show()`** teď navíc `abort_unless`uje
  neschválené záznamy (404) — dřív šlo na pending/rejected hernu dojít přímo URL (jen nebyla v
  seznamu), i když to nikde neodkazovalo.
- **`RegistraceHernyController::create()`/`store()`** + `App\Http\Requests\StoreHernaRegistrationRequest`
  (validace, `authorize()` `true` — veřejný formulář). "PSČ" pole z `ui/`'s mocku se nevaliduje
  ani neukládá — `Herna` na něj nemá sloupec a nic ho nikde nečte, stejné zahazování jako
  `RuleCard`'s nepoužitý `iconVariant="balls"`.
- **Honeypot + rate limit jako minimální ochrana proti spamu** — skrytý input `company`
  (`class="hidden"`, viditelný jen v DOMu/form datech, ne uživateli) s `prohibited` pravidlem ve
  `FormRequest`u, plus `throttle:5,1` middleware na POST route. Žádná externí služba
  (reCAPTCHA apod.) zatím není zapojená — tohle jsou nejlevnější dvě obrany, které nevyžadují
  žádnou závislost navíc.
- **Otevírací doba** je 7 pevných řádků (na rozdíl od adminového `HernaForm`'s reorderable
  `Repeater`u) — veřejný formulář nepotřebuje měnit pořadí dnů, jen vyplnit/nevyplnit text u
  každého; `hours[Pondělí]` atd. jako název inputu, `RegistraceHernyController::store()` sestaví
  pole `[{day, text}]` jen z vyplněných řádků (`collect(...)->filter()`).
- **Souřadnice (`lat`/`lng`) jsou nepovinné** — prázdné pole se uloží jako `0` (`$validated['lat']
  ?? 0`, **ne** `?:` — to by na chybějícím klíči ve `validated()` poli spadlo na "Undefined array
  key", protože `nullable` pravidlo bez odeslané hodnoty klíč do pole vůbec nepřidá). Formulář
  admina instruuje, ať v tom případě doplní souřadnice při schvalování.
- **Po úspěšném odeslání** — `submitted` session flash swapne formulář za `<x-alert>` s
  poděkováním. **TODO (vědomě odložené, viz `RegistraceHernyController`):** vlastní děkovací
  stránka/stav (`ui/`'s mock žádný nenavrhl), e-mail se shrnutím pro administrátora, kopie
  e-mailu odesílateli.
- **`HernasTable`** má nové `Action::make('approve')`/`Action::make('reject')` — jednokliková
  moderace vedle stávající `EditAction` (ta zůstává pro úpravu obsahu před schválením), viditelná
  jen když `status === Pending`. Otestováno přes `Livewire::test(ListHernas::class)
  ->callTableAction('approve', $record)` (první použití Livewire testing helperu v projektu —
  dosavadní Filament testy byly jen HTTP-úrovňové smoke testy stránek).

### Homepage (`/`)

Mirrors `ui/src/index.njk`. `HomeController::index()` sestavuje 8 sekcí, každá buď reálný dotaz s **pevným, needitovatelným počtem** (layout gridů je stavěný na konkrétní počet karet, ne libovolné číslo), nebo čte z `HomepageSettings`.

- **`HomepageSettings`** (spatie-settings, stejný vzor jako `GeneralSettings`, `group()` `'homepage'`) — texty specifické pro tuhle stránku (titulky/podnadpisy/popisky tlačítek jednotlivých sekcí) + dva výběry:
  - `banner_1_id`/`banner_2_id` (nullable int) — `Select` na `Banner` v adminu. **Sekce se vůbec nevypíše, když je `null` nebo záznam smazaný** (`@if ($banner1) ... @endif` v `home.blade.php`) — ne prázdný/rozbitý banner.
  - `link_tile_ids` (pole int, uspořádané) — `Select::make(...)->multiple()->reorderable()` (Filament to umí nativně, žádný Repeater navíc není potřeba). Prázdné pole → `<x-link-tiles>` se vůbec nevypíše.
  - Defaultní hodnoty v `database/settings/..._create_homepage_settings.php` schválně ukazují na reálné seednuté záznamy (`banner_1_id=1`, `banner_2_id=2`, `link_tile_ids=[1,2,3,4]`) — na čerstvém `migrate:fresh --seed` tak homepage vypadá jako `ui/`'s prototyp bez ručního nastavování v adminu.
- **`GeneralSettings::$cmbs_tv_url`** (ne `HomepageSettings`) — odkaz na ČMBS TV kanál v poznámce pod sekcí Turnaje. Věta `"Přímé přenosy z turnajů sledujte na ČMBS TV"` zůstává **hardcoded** v `components/tournaments.blade.php` (strukturální design copy s ikonkou, ne redakční obsah — RichEditor by tu byl zbytečné riziko rozbití stylu/struktury za cenu minimální flexibility), editovatelné je jen cílové URL. Patří do `GeneralSettings`, ne `HomepageSettings`, protože je to sitewide fakt nezávislý na konkrétní stránce (kdyby se poznámka objevila i jinde).
- **Featured articles** (`hp-featured-articles`) — 5 nejnovějších článků (`->orderByDesc('published_at')->take(5)`), první je `<x-article-card-main>` (velká hero karta), zbylé 4 `<x-article-card-compact>` (malý řádek) — dvě odlišné karty od `article-card.blade.php` (ten je `card_article_grid` varianta pro `/novinky` listing, jiná CSS třída). Žádný editovatelný titulek (jen `sr-only "Novinky"` pro čtečky, stejně jako `ui/`), jen popisek tlačítka.
- **Notices** (`hp-notices`) — 3 nejnovější (`Notice::orderByDesc('published_at')->take(3)`), `size="md"` kompaktní `<x-notice-card>`. Pojmenováno `hp-notices`, ne jen `notices`, ať nekoliduje s public listing stránkou (jiná CSS třída `.c-section--notices` vs. `.c-news-grid__*`).
- **Tournaments** — 4 nejbližší (`Tournament::currentAndUpcoming()->orderedByStartDate()->take(4)`), sdílí `components/tournaments.blade.php` s `/turnaje` preview stránkou (teď včetně ČMBS TV poznámky, viz výše).
  - **Podmíněný border/padding na švu s Leaderboards, když je Banner 2 prázdný** — Tournaments i Leaderboards mají stejné `bg-gray-100 border-top border-bottom`; normálně je odděluje Banner 2 sekce, ale bez ní jsou přímo za sebou. Podle `skills/ui-component-guide.md`'s PAST pravidla (dvě stejnobarevné sekce po sobě nesmí mít dvojitý border/padding na společném švu) `home.blade.php` používá `@class(['bg-gray-100', 'border-top', 'border-bottom' => $banner2])` na Tournaments a `@class(['bg-gray-100', 'border-bottom', 'border-top' => $banner2, 'pt-none' => ! $banner2])` na Leaderboards — každá sekce si nechá border jen na svém vnějším okraji té "šedé skupiny", a **následující** sekce (Leaderboards) ztrácí horní padding, ne předchozí. Otestováno v `PublicPagesTest::test_homepage_drops_inner_border_and_padding_when_banner_2_is_empty`.
- **Leaderboards** — **všechny** `Leaderboard` záznamy (žádný `take()`), ale **jen top 5 z každého** (`entries` má 10) — `<x-leaderboards :max-entries="5">`, které to posílá do `<x-leaderboard :max-entries="...">` (`array_slice($entries, 0, $maxEntries)`). Budoucí `/souteze` stránka použije stejné komponenty bez `max-entries` (= všech 10).
- **Link Tiles** — `LinkTile::whereIn('id', $settings->link_tile_ids)->get()->sortBy(...)->values()` — `whereIn` nezachovává pořadí, řadí se ručně podle pozice v uloženém poli ID.
- **Partners** — **všichni** partneři (`Partner::orderBy('sort_order')->get()`, žádný limit), přes `hp-partners` (obyčejná zeď log bez odkazu/jména — jiná komponenta než `partner-card.blade.php`, který je pro plnou `/partneri` mřížku s rámečkem+jménem+odkazem).
- **`<x-banner>`** — generická komponenta 1:1 podle `macros/banner.njk`, používaná pro OBĚ banner sekce (event promo i cta) — `Banner` entita je od začátku sjednocená pro obě varianty (`color` enum + `buttons` pole), takže není potřeba dvě různé komponenty. `text` je rich HTML (`{!! !!}`), tlačítka berou barvu z banneru (`buttons` neukládá barvu za tlačítko, jen `variant`).
- **Robustnost na prázdná data**: `featuredArticle` (nikdy null v provozu, ale chrání proti čerstvé instalaci bez článků) je za `@if`, stejně jako oba banner sloty a link tiles — `notices`/`tournaments`/`leaderboards`/`partners` bezpečně vykreslí prázdnou sekci (jen nadpis, žádná karta), pokud nic neexistuje, žádný `@if` navíc není potřeba (nejde o `null`, jen o prázdnou kolekci ve `@foreach`).

### Widgety (sekce stránek) → Blade views/komponenty
- Stejný princip jako makra, ale často už s reálnými daty místo mock JSON — widget přijímá Eloquent kolekci/model místo pole z `ui/src/_data/*.json`.
- Datový tvar (jména klíčů v `items`) drž pokud možno stejný jako v mock JSON, ať je port 1:1 a ne přejmenovávání polí navíc.

### Zpětný směr (výjimka)
Pokud se v `web/` objeví nutná drobná úprava (kvůli reálným datům, edge case), přenes ji **zpět** do `ui/` co nejdřív (a do mock dat), ať `ui/` zůstane aktuální referencí pro příští portování.

---

## 4. Datový model

Tvar polí vycházej z `ui/src/_data/*.json` (turnaje, kluby, herny, zebricky, kalendarUdalosti, partneri, souteze) — jsou to fakticky hotové "schéma návrhy".

* **Obrázky/loga (jeden obrázek na entitu):** obyčejný `string` sloupec (relativní cesta na disku, ne absolutní URL) + Filament `FileUpload` (`->directory('<entita>')`, `->image()`, `->disk('public')`, `->visibility('public')`). Model má accessor `<pole>_url` (`Attribute::get(fn () => $this->logo ? Storage::disk('public')->url($this->logo) : null)`), který teprve v Blade dává plnou URL — viz `app/Models/Partner.php` jako referenční příklad. **Nepoužíváme Spatie Media Library** (přidali bychom komplexitu navíc — polymorfní tabulka, konverze — kterou zatím nic v projektu nevyžaduje). Až narazíme na entitu s víc obrázky (galerie u herny/článku), řešíme to jako samostatné rozhodnutí až tehdy, ne teď dopředu.
  - **PAST: `FileUpload::make(...)` bez explicitního `->disk('public')` tiše ukládá na `.env`'s `FILESYSTEM_DISK` (default `local`)**, což v Laravel 11+ scaffoldu míří na `storage/app/private` — složku bez veřejné URL. Model i tak počítá `<pole>_url` přes `Storage::disk('public')`, takže výsledek je rozbitý obrázek (a v adminu chybí náhled u vyplněného pole — vypadá to jako jen prázdný upload widget), protože soubor fyzicky leží jinde, než kde ho URL hledá. Seedovaná data to nikdy neprojevila, protože seedery píšou rovnou přes `Storage::disk('public')->put(...)`, ne přes Filament formulář. Každé `FileUpload` pole musí mít `->disk('public')` výslovně — bez ohledu na `.env`'s default.
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
* **Auto-generovaný `slug_cs`/`slug_en` pro entity s reálným per-záznamovým routováním** (na rozdíl od entit, které mají jen listing bez detailu) — sdílený `HasSlug` trait (`app/Models/Concerns/HasSlug.php`): `protected static function bootHasSlug()` s `static::creating(...)`, doplní **oba** sloupce ze zdrojového pole jen pokud nejsou zadané, s `-2`/`-3`... při kolizi (řešeno zvlášť per sloupec). Zdrojové pole je defaultně `name` — pokud model nazývá svůj titulek jinak (`Article`/`Notice` mají `title`, ne `name`), přepiš `protected static function slugSourceField(): string { return 'title'; }` v modelu. Použij `use HasSlug;` v modelu + `slug_cs`/`slug_en` sloupce (obě `unique()`) v migraci. Slugy jsou **per jazyk** (dvě různé URL, ne jeden sdílený slug) — viz "Dvojjazyčný obsah (CZ/EN)" níže. Trait sám pozná, jestli je zdrojové pole translatable (např. `Article::title`) nebo obyčejný string (např. `Club::name`, `Herna::name` — vlastní jména se nepřekládají) a podle toho vezme CZ/EN text, nebo stejný text pro oba slugy.
* **`Banner`** (`title`, `text` rich-text, `image`, `tag_text`, `meta_text`, `color`, `buttons` JSON, `sort_order`) — sdílená entita pro `ui/`'s `eventBanner()` i `cta()` widgety, oba jen tenké obálky nad stejné `macros/banner.njk` (2 reálné příklady z `index.njk`: "Federal Cup 2026" — `color=accent`, 1 tlačítko — a "Hraješ s kamarády..." — `color=primary`, 2 tlačítka). Žádný `is_active`/stavový sloupec a žádný slug/veřejná stránka — `Banner` je čistě obsahová knihovna k výběru, **umístění na homepage (které ze 2 "děr" ukazuje který banner, nebo žádný) bude řešit budoucí `HomepageSettings`** (nullable `banner_slot_1_id`/`banner_slot_2_id`, vybírané přes Filament `Select`), ne vlastnost samotného Banneru — vyhne se to nejednoznačnosti "víc aktivních najednou" u boolean flagu.
  - `color`: `BannerColor` PHP enum (`primary`/`accent`/`dark`) — uzavřený seznam přímo podle `button.njk`'s `color` parametru (`tag.njk` má navíc `gray`/`gold`/`gold-light`, ale Banner je nikdy nepoužívá, takže užší enum stačí). Implementuje `HasColor` pro barevný badge v tabulce/filtru, stejný vzor jako `HernaStatus`.
  - `buttons`: JSON sloupec, pole `{text: {cs, en}, url, variant}` (max 2 položky, obě nepovinné) — malá ohraničená vždy-pohromadě data, stejný princip jako `Herna::$hours`. Ve Filamentu `Repeater::make('buttons')->maxItems(2)`, stejný vzor jako `HernaForm`'s `hours` repeater. `text` je jediná translatable část jednoho tlačítka (`TextInput::make('text.cs')`/`text.en')` — čistě nested dot-path uvnitř repeater itemu, **bez** `TranslatableTabs`/`HasTranslatableFormFields` (ty řeší translatable *top-level* Eloquent atribut přes `attributesToArray()`; tady je `buttons` obyčejný `'array'` cast bez spatie translatable zapojení, takže Filament to zvládne nativně). `url`/`variant` zůstávají sdílené pro oba jazyky (viz "Vědomě odložené" níže). `variant` (solid/outline/link) je jen inline `Select` options pole v `BannerForm`, ne vlastní enum třída — je to čistě vnitřní volba repeateru, nikde jinde se nepoužívá/nedotazuje.
  - `text` je `RichEditor` (ne `Textarea`) a v Blade se vypisuje přes `{!! !!}` — stejný "rich-text pole" vzor jako FAQ odpověď/`Article::$body`.
* **`Leaderboard`** (`title`, `featured` bool, `entries` JSON, `sort_order`) — zrcadlí `ui/`'s `macros/leaderboard.njk`/`widgets/leaderboards.njk`. `entries` je pole `{name, club}` **v pořadí = umístění** (rank se nikde neukládá, počítá se z pozice v poli, stejně jako `ui/`'s `loop.index`) — malá ohraničená vždy-pohromadě data, stejný princip jako `Herna::$hours`/`Banner::$buttons`, jen navíc s `->reorderable()` v Repeateru (na rozdíl od Bannerových tlačítek tady pořadí přímo *je* obsah, ne jen kosmetika). `club` je nullable — týmové žebříčky (např. "Extraliga Týmů") mají jen název týmu jako `name`, žádný klub.
  - `ui/`'s `_data/souteze.json` (10 hráčů, použito na `/souteze`) a `_data/zebricky.json` (5 hráčů, homepage) byla ve skutečnosti stejná data, jen homepage měla ručně oříznuté na 5 — sjednoceno do jednoho `entries` s plnými 10, homepage si vezme `->take(5)` sama.
  - Žádné pole pro budoucí cron/robot (žádné `external_id`/mapovací slug) — až se bude psát, prostě přepíše celé `entries` najednou (`Leaderboard::update(['entries' => [...]])`), žádné row-by-row mapování u JSON sloupce není potřeba. Neřešeno dopředu, dokud není konkrétní specifikace.
  - `linkText`/`linkUrl` z macra **nejsou ve entitě** (žádný sloupec) — v `ui/` je `linkUrl` vždy jen `"#"` (žádná per-série stránka zatím neexistuje), takže `components/leaderboard.blade.php` je nechává na defaultních hodnotách (`"Detail série"`/`"#"`, `"Celý žebříček"` jen když `featured`). Přidat až bude existovat reálný cíl odkazu.
  - Komponenty `components/leaderboard.blade.php` (jedna karta) a `components/leaderboards.blade.php` (sekce/widget) se teď používají na homepage (viz sekce "Homepage" níže, `max-entries=5`) — zapojení do skutečné `/souteze` stránky (velký statický obsah s jump-nav, samostatný rozsáhlý úkol, tam by šly beze změny s plnými 10 entries) je zatím odložené, stejný přístup jako u Klubů/Heren.
* **`LinkTile`** (`title`, `url`, `image`, `sort_order`) — zrcadlí `ui/`'s `macros/card/tile.njk`/`widgets/link-tiles.njk`. Sdílený obsahový pool napříč stránkami — `ui/`'s `index.njk` a `faq.njk` mají doslova identickou čtveřici dlaždic ("Začni hrát"/"Pravidla"/"Systémy soutěží"/"O svazu") zkopírovanou na obou místech; tady existuje jen jednou.
  - **Výběr "které dlaždice na které stránce a v jakém pořadí" není vlastností `LinkTile`** — protože se stejná dlaždice může objevit na víc stránkách, v různém pořadí, nebo se vůbec nemusí zobrazit — je to otázka nastavení té konkrétní stránky (uspořádané pole ID, stejný princip jako plánované `HomepageSettings::$banner_slot_id`), ne sloupec na `LinkTile` (žádný `is_active`, žádná pivot vazba na "page" entitu, protože stránky jako homepage/FAQ nejsou DB entity). Až se bude stavět konkrétní stránka, přidá se jí vlastní settings pole (`HomepageSettings::$link_tile_ids` apod.), které vyřeší `LinkTile::whereIn('id', $ids)->get()->sortBy(...)` v pořadí podle uloženého pole ID.
  - `components/link-tile.blade.php` (jedna dlaždice) a `components/link-tiles.blade.php` (sekce, bere `items` už vyfiltrované/seřazené volajícím) se teď používají na homepage — výběr/pořadí řeší `HomepageSettings::$link_tile_ids`, viz sekce "Homepage" níže. Zapojení do `/faq` (druhé místo, kde `ui/` používá stejné dlaždice) je zatím odložené.

---

## 5. Dvojjazyčný obsah (CZ/EN)

Web bude muset být CZ/EN. Zatím je připravená jen **datová vrstva + admin** (Filament formuláře) — **veřejný routing s `/en` prefixem, jazykový přepínač v headeru a překlad statických UI textů (`ui/`, Blade chrome) zatím NEJSOU** a přijdou až po dobudování správy obsahu. `app.locale`/`app.fallback_locale` jsou `cs` (viz `.env`) — veřejný web je tedy dál čistě český, jen editovatelný obsah je teď připravený na doplnění anglického překladu bez další migrace.

* **Balíček: `spatie/laravel-translatable` (jen základní balíček, ne `filament/spatie-laravel-translatable-plugin`)** — ten oficiální Filament plugin zatím nepodporuje Filament v5 (`composer require` selže, vyžaduje `filament/support` v3.x; ověř před případnou aktualizací balíčků, jestli se to nezměnilo). Formát DB sloupce (`{"cs": "...", "en": "..."}` JSON) je záměrně identický s tím, co plugin očekává, takže až přidá podporu v5, přechod je jen výměna Filament-vrstvy (`App\Filament\Support\TranslatableTabs` → pluginové komponenty) — **žádná migrace dat**.
* **Který sloupec je translatable a který ne:** volný prezentační text (`title`, `excerpt`, `body`, `about_text`, `question`/`answer`...) → translatable JSON sloupec. Vlastní jména, adresy, URL, barvy/enum hodnoty **ne** — `Club::name`, `Herna::name`, `Partner::name` apod. zůstávají obyčejný string (název klubu/herny/partnera se nepřekládá). `FaqGroup::name` taky zůstává plain — je to čistě interní admin/kód identifikátor (viz `FaqController`), nikdy se veřejně nevykresluje.
* **Vzor na modelu** (referenční příklad: `app/Models/Article.php`):
  ```php
  use App\Models\Concerns\HasTranslatableFormFields;

  class Article extends Model
  {
      use HasTranslatableFormFields;

      public array $translatable = ['title', 'excerpt', 'body'];

      protected $fillable = [
          'title', 'title_translations',       // obojí fillable, viz níže
          'excerpt', 'excerpt_translations',
          'body', 'body_translations',
          // ...ostatní sloupce
      ];
  }
  ```
  `HasTranslatableFormFields` (`app/Models/Concerns/HasTranslatableFormFields.php`) zabaluje `Spatie\Translatable\HasTranslations` a navíc pro každé translatable pole vystaví **virtuální** `{field}_translations` atribut (`['cs' => ..., 'en' => ...]`) — bez psaní per-pole `Attribute::make()` accessoru na každém modelu zvlášť. `$model->title` (bez suffixu) zůstává obyčejný string v aktuální lokalizaci (spatie magic accessor) — veřejný Blade/controller kód se díky tomu **vůbec nemusí měnit**, jen admin formuláře cílí na `{field}_translations.cs`/`.en`. Obě varianty (`title` i `title_translations`) patří do `$fillable` — `title` kvůli factories/seederům (obyčejný string → uloží se jen do `cs`, `en` zůstane nepřeložené a čte se přes fallback), `title_translations` kvůli Filament formulářům (viz níže).
* **Vzor ve Filament formuláři — JEDEN Tabs pár na celý resource, ne jeden na pole** (referenční příklad: `app/Filament/Resources/Articles/Schemas/ArticleForm.php`):
  ```php
  Section::make('Obsah')
      ->components([
          TranslatableTabs::make([
              'title' => fn (string $locale) => TextInput::make('title')
                  ->label('Titulek')
                  ->required($locale === 'cs'),
              'excerpt' => fn (string $locale) => Textarea::make('excerpt')
                  ->label('Perex')
                  ->rows(3),
              'body' => fn (string $locale) => RichEditor::make('body')
                  ->label('Obsah článku'),
          ]),
      ]),
  ```
  `App\Filament\Support\TranslatableTabs::make(array $fields, ...)` bere **všechna** translatable pole daného resource najednou (ne volání po jednom poli) a postaví z nich jeden CZ/EN `Tabs` — jeden přepínač jazyka pro celý záznam, ne jeden u každého pole zvlášť (to bylo matoucí — první iterace to dělala per-pole, přestavěno na základě zpětné vazby). Klíč pole v poli `$fields` → `statePath` `{field}_translations.{locale}`, closure dostane `$locale` a řídí per-jazyk detaily (`->required($locale === 'cs')` apod.). **Needitovatelná pole (slug, obrázek, vztahy, datum...) zůstávají mimo** tenhle blok, ve svých původních sekcích — jen translatable pole se přesunou dohromady do vlastní sekce (typicky nazvané "Obsah"), i když byla předtím rozeseta po více sekcích (viz `ClubForm`: `about_text` bylo v "Základní údaje", `recruitment_text` v "Nábor" — obě teď spolu v jedné `TranslatableTabs::make([...])`). Tabulky (`Tables/*Table.php`) se **nemění** — `TextColumn::make('title')` funguje beze změny (magic accessor).
  * **PAST (velmi snadné zopakovat): field state se musí přepojit přes `->statePath(...)`, ne `->name(...)`.** `->name()` mění jen label/id komponenty, ne kam se váže její state — vypadá to, že to funguje (žádná chyba při vyplňování), ale ve skutečnosti oba jazykové taby zůstanou tiše navázané na původní `::make()` argument (tj. na sebe navzájem). Projeví se to různě podle typu pole — u `TextInput` prostě obě pole ukládají/čtou to samé, u `RichEditor` to spadne (Tiptap dostane pole místo stringu → `Undefined array key "content"`). Ověřeno end-to-end přes `Livewire::test(CreateXxx::class)->fillForm([...])->call('create')`, ne jen "stránka se načte" smoke testem — ten chybu neodhalí.
  * **PAST: `RichEditor`/Tiptap (`ueberdosis/tiptap-php`) padá na prázdném **stringu** (`''`), ne na `null`.** Filament's `RichEditorStateCast` má `$state ?? [default doc]` fallback jen pro `null`. Proto `HasTranslatableFormFields::getAttribute()` pro nepřeloženou lokalizaci vrací `null` (přes `getTranslation($field, $locale, useFallbackLocale: false)`), **ne** `''`.
  * **U `spatie/laravel-settings` stránek (`ManageHomepageSettings`, `ManageKlubySettings`, `ManageHernySettings`) jde "jeden Tabs pár na celý resource" ještě dál — jeden Tabs pár na **celou admin stránku**, napříč původně oddělenými `Section`y** (Homepage: Novinky/Zprávy VV/Turnaje/Žebříčky/Partneři byly každá svůj vlastní `TranslatableTabs::makeForSettings([...])`, teď je to jedno volání se všemi poli pohromadě v jedné sekci "Obsah"). Vědomé rozhodnutí (na žádost uživatele) — cena je, že překladatelné pole ztratí vizuální sousedství se svými needitovatelnými sourozenci (Banner select zůstává ve své vlastní sekci, ale fyzicky odděleně od zbytku Novinek). Kvůli tomu mají pole v jedné velké tabce popisky s prefixem podle původní sekce (`'Zprávy VV — Titulek'`, ne jen `'Titulek'`), jinak by formulář obsahoval několik nerozlišitelných polí se stejným labelem.
  * `relationship('category', 'name')` selecty (a `Model::pluck('title', 'id')`) obcházejí magic accessor (SQL-level projekce) — u translatable cílového sloupce potřebují buď `->getOptionLabelFromRecordUsing(fn ($record) => $record->name)`, nebo `->get()->pluck('title', 'id')` místo přímého `pluck()` na query builderu. Referenční příklady: `ArticleForm`'s kategorie select, `ManageHomepageSettings`'s banner/link-tile selecty.
* **Slugy jsou per jazyk** (`slug_cs`/`slug_en`, obě `unique()`) — viz `HasSlug` výše. Veřejné routy zatím používají jen `slug_cs` (`{article:slug_cs}` v `routes/web.php`) — `slug_en` existuje a je editovatelný v adminu, ale nic ho zatím nečte (čeká na budoucí `/en` routing).
* **`spatie/laravel-settings` třídy (`HomepageSettings`, `GeneralSettings`) — jiný vzor než Eloquent modely**, protože nejsou Eloquent (žádný `attributesToArray()`/mutator mechanismus, `HasTranslatableFormFields` se sem nehodí). Místo JSON sloupce má translatable textová vlastnost prostý **`{field}_en` sourozenecký property** (např. `notices_title` + `notices_title_en`) — nesufixovaná vlastnost dál znamená český text, takže **žádné volající místo (Blade, jiné modely) se nemuselo měnit**, jen přibyla nová `_en` vlastnost, kterou zatím nic nečte. Ve Filament formuláři `App\Filament\Support\TranslatableTabs::makeForSettings([...])` (ne `::make()`) — stejné API (`field => closure`), jen jinak generovaný `statePath` (`{field}` pro cs, `{field}_en` pro en, ne `{field}_translations.{locale}`). Referenční příklad: `app/Filament/Pages/ManageHomepageSettings.php`. Nová `_en` vlastnost se přidává i do settings migrace (`database/settings/...php`, `$this->migrator->add('homepage.pole_en', '')`) — needituj `.env`/`config/app.php` navíc, ty jen řídí aktuální locale, ne translatable schema.
* **Seedery: `updateOrCreate(['title' => $x], ...)` na translatable poli tiše přestane fungovat** (vytváří duplikáty při každém `db:seed`, protože hledá přesnou shodu s celým JSON blobem, ne s CZ hodnotou uvnitř) — použij `Model::updateOrCreateByTranslation('title', $x, $attributes)` (na `HasTranslatableFormFields`, hledá přes `whereJsonContainsLocale($field, 'cs', $value)`). Stejně tak `Model::where('name', $x)` lookup (např. hledání kategorie podle jména) potřebuje `whereJsonContainsLocale('name', 'cs', $x)`. Referenční příklady: `database/seeders/ArticleSeeder.php`, `ArticleCategorySeeder.php`.
* **Vědomě odložené (ne přehlédnuté):**
  - `Herna::$hours` (text u každého dne, např. "Zavřeno") je JSON pole s vnořeným textem — nepřekládá se zatím, řeší se až jako samostatné rozhodnutí u toho konkrétního widgetu. (`Banner::$buttons`'s `text` **je** translatable — viz výše.)
  - `LinkTile::$url` a `Tournament::$url` **zůstávají jeden sdílený odkaz pro oba jazyky** (ne per-jazyk) — až přibude možnost odkazovat na interní stránku místo ručně psaného URL, vyřeší se to samo (interní stránka bude mít vlastní CZ/EN slug, systém zvolí správnou URL podle locale). Ruční řešení dvou URL polí teď by bylo zbytečné, kdyby to appka za pár měsíců udělala jinak.
  - `ArticleCategory::$name`/`TournamentCategory::$name` měly dřív DB `unique()` — na JSON sloupci by to hlídalo unicitu celého blobu, ne per-jazyk, takže constraint byl při migraci na translatable sloupec odstraněný (editorská disciplína zatím, expression index při potřebě později).
  - `ClubMember` (jen `name`/`photo`) — žádné volné textové pole tam není, nic k překladu.

---

## 6. Filament Resources

* Generuj přes `php artisan make:filament-resource <Model> --generate` (odvodí formulář/tabulku z DB schématu), pak dolaď: `TextInput` pro cestu k obrázku přepiš na `FileUpload` (viz bod 4), přidej `ImageColumn` do tabulky pro náhled.
* Konvence pojmenování a struktura souborů (Filament v5): `app/Filament/Resources/<Entity>/{<Entity>Resource.php, Pages/, Schemas/<Entity>Form.php, Tables/<Entity>sTable.php}` — necháváme, jak to generátor vytvoří.
* `--generate` u čerstvě vytvořeného modelu (hned po `make:model -mfs`, ještě před `php artisan migrate`) občas vrátí prázdné `Schema`/`Table` místo odvozených polí (pravděpodobně kvůli interaktivnímu dotazu na "title attribute" i přes `--no-interaction`) — pokud se to stane, napiš `Schema`/`Table` komponenty ručně podle sloupců migrace, generátor nezkoušej spouštět znovu.
* **Dashboard widgety** (`app/Filament/Widgets/`, auto-discovered přes `AdminPanelProvider`'s `discoverWidgets`) — první je `HernaStatsOverview` (`StatsOverviewWidget`, generováno `make:filament-widget --stats-overview`): počet heren čekajících na schválení (viz "Registrace herny" výše) + počet schválených, každá dlaždice `->url(HernaResource::getUrl('index', ['tableFilters' => [...]]))` proklikne rovnou na `/admin/hernas` s už aplikovaným filtrem stavu (Filament tabulky čtou `tableFilters` z query stringu samy, žádný vlastní kód navíc). **PAST: Filament widgety defaultně lazy-loadují** (`protected static bool $isLazy = true` v `CanBeLazy` traitu) — na dashboardu se nejdřív vykreslí placeholder a obsah dotáhne až následný Livewire request po JS inicializaci, takže obyčejný `$this->get('/admin')` test (bez JS) placeholder uvidí, ale text dlaždice ne. Pro levný widget (pár COUNT dotazů, žádný důvod schovávat ho za extra roundtrip) nastav `protected static bool $isLazy = false;` na widgetu — pak se vykreslí rovnou v prvním requestu a jde ho i takhle jednoduše otestovat (`tests/Feature/AdminResourcesTest.php::test_dashboard_shows_herna_moderation_stat`).

---

## 7. Ověření

1. `php artisan serve` + `npm run dev` — ověřit stránku v prohlížeči vedle `ui/` verze.
2. `vendor/bin/pint --dirty --format agent` po úpravě PHP souborů (Laravel Boost guideline).
3. Testy (Pest/PHPUnit) pro novou funkcionalitu — feature testy preferované před unit testy, viz `web/CLAUDE.md`.
4. **Ověření admin resource stránek vyžaduje skutečné přihlášení, ne jen kontrolu, že route existuje** — `curl` bez session na `/admin/<resource>` vrátí `302` (redirect na login) i když je za tím rozbitý formulář/tabulka, takže to nic neřekne o tom, jestli se Blade/Livewire fakt vykreslí. `tests/Feature/AdminResourcesTest.php` řeší tohle přes `actingAs($user)->get($url)->assertOk()` pro všechny resources najednou (s jedním reálným záznamem od každého modelu, ať se vykreslí i relace/enum sloupce, ne jen prázdná tabulka) — přidej sem novou resource, jak vznikne. Vyžaduje `App\Models\User implements Filament\Models\Contracts\FilamentUser` s `canAccessPanel(): true` — bez toho Filament mimo `local` prostředí (tedy i v testech) vrátí `403` i pro platně přihlášeného uživatele.
5. Podobně `tests/Feature/PublicPagesTest.php` pro veřejné stránky — kontroluje 200 **a** přítomnost chrome markerů (`c-header__nav`, `c-footer__nav`), ne jen 200 samotné (viz "Site chrome" výše). Přidej sem novou veřejnou route, jak vznikne.
