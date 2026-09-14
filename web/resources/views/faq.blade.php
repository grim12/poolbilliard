<!--
    Mirrors ui/src/faq.njk. Zatím BEZ site chrome (header/footer/page-hero/link-tiles/
    newsletter) — ty ještě nejsou portované z ui/, viz partneri.blade.php pro stejnou
    poznámku. Aside (kontakt + infoPanel) je hotový, protože nezávisí na chrome.
-->
<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Časté dotazy — Poolbilliard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <section class="c-section c-section--faq">
        <div class="c-container">
            <div class="c-section__grid">
                <div class="c-section__body">
                    <h2>Otázky a odpovědi</h2>
                    <x-faq
                        id-prefix="faq"
                        :items="$items->map(fn ($item) => ['question' => $item->question, 'answer' => $item->answer])->all()"
                    />
                </div>

                <aside class="c-section__aside">
                    <div class="c-faq-contact">
                        <p class="c-section__eyebrow">Kontakt</p>
                        <div class="c-faq-contact__list">
                            <a class="c-faq-contact__link" href="mailto:kontakt@poolbilliard.cz">
                                <x-heroicon-m-envelope width="18" height="18" />
                                kontakt@poolbilliard.cz
                            </a>
                            <a class="c-faq-contact__link" href="/sportovni-svaz/">
                                <x-heroicon-m-user-group width="18" height="18" />
                                Kontakty na výkonný výbor
                            </a>
                        </div>
                    </div>

                    <x-info-panel
                        title="Nenašli jste odpověď?"
                        text="Napište nám a rádi pomůžeme — na e-maily odpovídáme obvykle do 2 pracovních dnů."
                        button-text="Napsat e-mail"
                        button-url="mailto:kontakt@poolbilliard.cz"
                        heading-level="h3"
                    />
                </aside>
            </div>
        </div>
    </section>
</body>
</html>
