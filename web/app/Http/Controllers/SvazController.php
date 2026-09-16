<?php

namespace App\Http\Controllers;

use App\Models\CommitteeMember;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Notice;
use App\Settings\SvazSettings;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SvazController extends Controller
{
    /**
     * Mirrors ui/src/sportovni-svaz.njk. $settings holds the hero, the "Českomoravský
     * billiardový svaz" intro column, and the section titles; $members are CommitteeMember
     * records; $documentYears is built from real Document/DocumentCategory records (see
     * buildDocumentYears()); $notices reuses the same query HomeController uses for its
     * "Zprávy výkonného výboru" teaser.
     */
    public function index(SvazSettings $settings): View
    {
        return view('sportovni-svaz', [
            'settings' => $settings,
            'members' => CommitteeMember::orderBy('sort_order')->get(),
            'documentYears' => $this->buildDocumentYears(),
            'notices' => Notice::whereNotNull('published_at')->orderByDesc('published_at')->take(3)->get(),
        ]);
    }

    /**
     * Builds <x-documents>'s `years` array from real data instead of ui/'s hardcoded
     * _data/svazDokumenty.js: one panel per distinct year actually present on a Document
     * (newest first), plus a trailing "Obecné" panel for documents with no year (evergreen —
     * stanovy, formuláře). A year/category combination with no documents is dropped entirely
     * (mirrors ui/'s mock never showing an empty category). The first non-empty panel is the
     * one shown by default.
     *
     * @return array<int, array{label: string, active: bool, groups: array<int, array{title: string, count: int, files: array<int, array{name: string, meta: ?string, url: ?string}>}>}>
     */
    private function buildDocumentYears(): array
    {
        $categories = DocumentCategory::orderBy('sort_order')->get();
        $documents = Document::orderBy('sort_order')->get();

        $panels = $documents->pluck('year')->filter()->unique()->sortDesc()->values()
            ->map(fn (int $year) => $this->buildDocumentYearPanel((string) $year, $categories, $documents->where('year', $year)));

        $evergreenDocuments = $documents->whereNull('year');

        if ($evergreenDocuments->isNotEmpty()) {
            $panels->push($this->buildDocumentYearPanel('Obecné', $categories, $evergreenDocuments));
        }

        return $panels
            ->filter(fn (array $panel) => count($panel['groups']))
            ->values()
            ->map(fn (array $panel, int $index) => [...$panel, 'active' => $index === 0])
            ->all();
    }

    /**
     * @param  Collection<int, DocumentCategory>  $categories
     * @param  Collection<int, Document>  $documentsForYear
     * @return array{label: string, active: bool, groups: array<int, array{title: string, count: int, files: array<int, array{name: string, meta: ?string, url: ?string}>}>}
     */
    private function buildDocumentYearPanel(string $label, Collection $categories, Collection $documentsForYear): array
    {
        $groups = $categories
            ->map(function (DocumentCategory $category) use ($documentsForYear) {
                $files = $documentsForYear->where('document_category_id', $category->id)->values();

                return [
                    'title' => $category->name,
                    'count' => $files->count(),
                    'files' => $files->map(fn (Document $document) => [
                        'name' => $document->name,
                        'meta' => $document->meta_text,
                        'url' => $document->file_url,
                    ])->all(),
                ];
            })
            ->filter(fn (array $group) => $group['count'] > 0)
            ->values()
            ->all();

        return ['label' => $label, 'active' => false, 'groups' => $groups];
    }
}
