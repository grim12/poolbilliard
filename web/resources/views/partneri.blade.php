<!--
    Mirrors ui/src/partneri.njk. Zatím BEZ site chrome (header/footer/page-hero/newsletter)
    — ty ještě nejsou portované z ui/, tohle je jen ověření Partner feature end-to-end
    (DB -> Model -> Filament resource -> Blade -> route). Doplnit, až se portuje layout.
-->
<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Partneři — Poolbilliard</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <section class="c-section c-section--partner-directory">
        <div class="c-container">
            <div class="c-section__grid">
                @foreach ($partners as $partner)
                    <x-partner-card :name="$partner->name" :image="$partner->logo_url" :url="$partner->url" />
                @endforeach
            </div>
        </div>
    </section>
</body>
</html>
