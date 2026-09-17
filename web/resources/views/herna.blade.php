{{-- Mirrors ui/src/herna.njk — club-hero (shared with Kluby) + herna-detail. --}}
<x-layouts.app
    :title="$herna->seo_title ?: $herna->name"
    :description="$herna->seo_description ?: ($herna->about_text ? \Illuminate\Support\Str::of($herna->about_text)->stripTags()->squish()->limit(155)->toString() : $herna->name.', '.$herna->city.' — '.__('herna v katalogu Českého poolbilliardu.'))"
    :og-image="$herna->seo_image_url"
>
    <x-club-hero
        :title="$herna->name"
        :city="$herna->city"
        :region="$herna->region?->getLabel()"
        :back-text="__('Zpět na herny')"
        :back-url="\App\Support\Locale::route('herny')"
    />

    <x-herna-detail :herna="$herna" />

    <x-newsletter />
</x-layouts.app>
