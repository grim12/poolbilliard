{{-- Mirrors ui/src/klub.njk — club-hero + club-detail. --}}
<x-layouts.app :title="$club->name.' — Poolbilliard'">
    <x-club-hero
        :title="$club->name"
        :city="$club->city"
        :region="$club->region?->getLabel()"
        :image="$club->image_url"
        back-url="/kluby"
    />

    <x-club-detail :club="$club" />

    <x-newsletter />
</x-layouts.app>
