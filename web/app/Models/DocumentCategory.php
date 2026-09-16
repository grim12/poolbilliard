<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\DocumentCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A document group on /sportovni-svaz's document archive ("Soutěžní předpisy", "Zápisy ze
 * schůzí VVS", ...) — same "opakovaná dvojice volný text + taxonomie" reasoning as
 * ArticleCategory/TournamentCategory: admin-manageable (the federation may introduce a new
 * category over time), not a closed enum. `name` is translatable — unlike FaqGroup::name
 * (an internal lookup key, never rendered), this one IS the public accordion group heading.
 */
class DocumentCategory extends Model
{
    /** @use HasFactory<DocumentCategoryFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'name_translations',
        'sort_order',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
