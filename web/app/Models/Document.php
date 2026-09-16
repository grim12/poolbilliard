<?php

namespace App\Models;

use Database\Factories\DocumentFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

/**
 * One downloadable file in /sportovni-svaz's document archive — mirrors one `files[]` entry in
 * ui/src/_data/svazDokumenty.js. `year` is nullable: null means "Obecné" (evergreen documents
 * like stanovy/formuláře, not tied to a competition season) — same idea as
 * Tournament::dateText()'s "no date yet" fallback, just the opposite direction (no year is a
 * real, permanent state here, not a placeholder).
 *
 * `name` isn't translatable — these are official Czech document titles (a PDF's actual content
 * stays Czech regardless of site language), same reasoning as Club::name.
 *
 * The "PDF · 850 KB" meta text ui/'s mock hand-typed per file is computed here from the real
 * uploaded file instead (extension + Number::fileSize()) — same "computed over manually
 * maintained field" principle as Tournament::dateText()/soon().
 */
class Document extends Model
{
    /** @use HasFactory<DocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'document_category_id',
        'year',
        'name',
        'file',
        'sort_order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::get(fn () => $this->file ? Storage::disk('public')->url($this->file) : null);
    }

    protected function metaText(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->file || ! Storage::disk('public')->exists($this->file)) {
                return null;
            }

            $extension = strtoupper(pathinfo($this->file, PATHINFO_EXTENSION));
            $size = Number::fileSize(Storage::disk('public')->size($this->file), precision: 0);

            return "{$extension} · {$size}";
        });
    }
}
