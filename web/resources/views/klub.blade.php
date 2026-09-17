{{-- Mirrors ui/src/klub.njk — club-hero + club-detail. --}}
<x-layouts.app
    :title="$club->seo_title ?: $club->name"
    :description="$club->seo_description ?: ($club->about_text ? \Illuminate\Support\Str::of($club->about_text)->stripTags()->squish()->limit(155)->toString() : $club->name.', '.$club->city.' — '.__('klub Českého poolbilliardu.'))"
    :og-image="$club->seo_image_url ?: $club->image_url"
>
    <x-club-hero
        :title="$club->name"
        :city="$club->city"
        :region="$club->region?->getLabel()"
        :image="$club->image_url"
        :back-url="\App\Support\Locale::route('kluby')"
    />

    <x-club-detail :club="$club" />

    <x-newsletter />
</x-layouts.app>
