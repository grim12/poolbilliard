{{--
    Preview stránky pro Tournament entitu — ui/ nemá pro tenhle grid samostatnou route (tam je
    to jen homepage sekce, viz widgets/tournaments.njk, + plná stránka Kalendář, která má navíc
    interaktivní filtr/kalendář JS, zatím neportované). Site chrome (header/footer) teď zapojené
    přes <x-layouts.app>.
--}}
<x-layouts.app title="Turnaje — Poolbilliard">
    <x-tournaments
        title="Nejbližší turnaje"
        :items="$tournaments"
        calendar-url="/kalendar/"
        :stream-url="$cmbsTvUrl"
    />
</x-layouts.app>
