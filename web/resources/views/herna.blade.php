{{-- Mirrors ui/src/herna.njk — club-hero (shared with Kluby) + herna-detail. --}}
<x-layouts.app
    :title="$herna->name.' — Poolbilliard'"
    :description="$herna->about_text ? \Illuminate\Support\Str::of($herna->about_text)->stripTags()->squish()->limit(155)->toString() : $herna->name.', '.$herna->city.' — herna v katalogu Českého poolbilliardu.'"
>
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
