{{--
    Mirrors ui/src/sportovni-svaz.njk. $settings: App\Settings\SvazSettings — hero, the ČMBS
    intro column (text/links/tasks), the Výkonný výbor column's intro text, and the
    documents/notices section titles. $members: CommitteeMember collection. $documentYears:
    built in SvazController from real Document/DocumentCategory records. $notices: latest 3
    Notice records (same query as the homepage's "Zprávy výkonného výboru" teaser).
--}}
<x-layouts.app
    :title="$settings->hero_title.' — Poolbilliard'"
    description="Výkonný výbor, dokumenty a organizační struktura Českého svazu poolbilliardu."
>
    <x-page-hero :title="$settings->hero_title" :text="$settings->hero_text" />

    <section class="c-section c-section--svaz-info">
        <div class="c-container">
            <div class="c-section__grid">
                <div>
                    <h2>{{ $settings->info_title }}</h2>
                    {!! $settings->info_text !!}

                    <div class="c-svaz-info__links">
                        <x-button text="Oficiální web ČMBS" :url="$settings->cmbs_website_url" variant="link" size="sm" icon="arrow-top-right-on-square" target="_blank" />
                        <x-button text="Stanovy ČMBS" :url="$settings->cmbs_bylaws_url" variant="link" size="sm" icon="arrow-top-right-on-square" target="_blank" />
                    </div>

                    <div class="c-svaz-info__divider"></div>

                    <div class="c-svaz-info__tasks">
                        <div>
                            <h3 class="mb-3">{{ $settings->tasks_title }}</h3>
                            <div class="c-svaz-info__links c-svaz-info__links--stacked">
                                @foreach ($settings->tasks as $task)
                                    <x-button :text="$task['text']" :url="$task['url']" variant="link" size="sm" icon="arrow-top-right-on-square" target="_blank" />
                                @endforeach
                            </div>
                        </div>
                        <img src="/uploads/cmbs-logo.png" alt="ČMBS" class="c-svaz-info__logo" loading="lazy" />
                    </div>
                </div>

                <div>
                    <h2>{{ $settings->committee_title }}</h2>
                    {!! $settings->committee_text !!}

                    <x-committee-list :members="$members" />
                </div>
            </div>
        </div>
    </section>

    <x-documents :title="$settings->documents_title" :years="$documentYears" class="pt-none" />

    <x-hp-notices
        :title="$settings->notices_title"
        :subtitle="$settings->notices_subtitle"
        :items="$notices"
        :more-url="route('zpravodajstvi.vykonny-vybor')"
        class="pt-none"
    />

    <x-newsletter />
</x-layouts.app>
