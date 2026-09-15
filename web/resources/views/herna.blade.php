{{-- Mirrors ui/src/herna.njk — club-hero (shared with Kluby) + herna-detail. --}}
<x-layouts.app :title="$herna->name.' — Poolbilliard'">
    <x-club-hero
        :title="$herna->name"
        :city="$herna->city"
        :region="$herna->region?->getLabel()"
        back-text="Zpět na herny"
        back-url="/herny"
    />

    <x-herna-detail :herna="$herna" />

    <x-newsletter />
</x-layouts.app>
