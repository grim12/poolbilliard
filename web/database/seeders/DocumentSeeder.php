<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DocumentSeeder extends Seeder
{
    /**
     * ui/src/_data/svazDokumenty.js fakes the *same* 4 competition-rules documents for every
     * year from 2014 to 2026 (only the year in the filename changes) purely so the mock has
     * something to show in each of 13 year tabs. Since Document now stores a real uploaded
     * file (see App\Models\Document's docblock), backfilling 13 years of placeholder PDFs
     * would be seed noise, not useful demo data — real past years won't retroactively get real
     * files anyway. So this seeds only the current season (2026) plus the evergreen
     * ("Obecné"/`year: null`) documents, which is enough for SvazController::buildDocumentYears()
     * to render a realistic year-tabs UI; further years get added for real through the admin
     * as each season closes.
     *
     * A tiny placeholder PDF is generated on the fly (not committed as a binary asset — there's
     * nothing real to mirror here, unlike PartnerSeeder's logos) so Document::$file_url/
     * $meta_text work against a real file on the "public" disk, same as production uploads.
     */
    private const MINIMAL_PDF = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 200 200]>>endobj\ntrailer<</Size 4/Root 1 0 R>>\n%%EOF";

    private const CURRENT_YEAR = 2026;

    private const DOCUMENTS = [
        ['category' => 'Soutěžní předpisy', 'year' => self::CURRENT_YEAR, 'name' => 'Soutěžní řád 2026'],
        ['category' => 'Soutěžní předpisy', 'year' => self::CURRENT_YEAR, 'name' => 'Prováděcí předpis soutěžního řádu 2026'],
        ['category' => 'Soutěžní předpisy', 'year' => self::CURRENT_YEAR, 'name' => 'Etický kodex hráče 2026'],
        ['category' => 'Soutěžní předpisy', 'year' => self::CURRENT_YEAR, 'name' => 'Kalendář soutěží 2026'],
        ['category' => 'Zápisy ze schůzí VVS', 'year' => self::CURRENT_YEAR, 'name' => 'Zápis ze schůze VVS 2026'],
        ['category' => 'Zápisy z VH sekce', 'year' => self::CURRENT_YEAR, 'name' => 'Zápis z valné hromady sekce 2026'],
        ['category' => 'Hospodaření sekce', 'year' => self::CURRENT_YEAR, 'name' => 'Hospodaření sekce 2026'],
        ['category' => 'Základní dokumenty', 'year' => null, 'name' => 'Stanovy ČMBS'],
        ['category' => 'Základní dokumenty', 'year' => null, 'name' => 'Registrační řád'],
        ['category' => 'Základní dokumenty', 'year' => null, 'name' => 'Etický kodex hráče'],
        ['category' => 'Formuláře', 'year' => null, 'name' => 'Přihláška do klubu'],
        ['category' => 'Formuláře', 'year' => null, 'name' => 'Žádost o hostování'],
        ['category' => 'Formuláře', 'year' => null, 'name' => 'Žádost o přestup'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disk = Storage::disk('public');
        // ->get()->pluck(...), not a query-builder pluck() — 'name' is a translatable JSON
        // column, and pluck() on the query builder would bypass the magic accessor and return
        // the raw JSON blob as the key instead of the plain Czech string.
        $categoryIds = DocumentCategory::get()->pluck('id', 'name');

        foreach (self::DOCUMENTS as $index => $document) {
            $storagePath = 'documents/'.str($document['name'].'-'.($document['year'] ?? 'obecne'))->slug().'.pdf';

            if (! $disk->exists($storagePath)) {
                $disk->put($storagePath, self::MINIMAL_PDF);
            }

            Document::updateOrCreate(
                ['name' => $document['name'], 'year' => $document['year']],
                [
                    'document_category_id' => $categoryIds[$document['category']],
                    'file' => $storagePath,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
