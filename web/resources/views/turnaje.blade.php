<!--
    Preview stránky pro Tournament entitu — ui/ nemá pro tenhle grid samostatnou route (tam je
    to jen homepage sekce, viz widgets/tournaments.njk, + plná stránka Kalendář, která má navíc
    interaktivní filtr/kalendář JS, zatím neportované). Zatím BEZ site chrome.
-->
<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Turnaje — Poolbilliard</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <x-tournaments
        title="Nejbližší turnaje"
        :items="$tournaments"
        calendar-url="/kalendar/"
    />
</body>
</html>
