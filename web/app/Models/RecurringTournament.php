<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslatableFormFields;
use App\Support\Seo;
use Database\Factories\RecurringTournamentFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Weekly/regular amateur club tournaments (e.g. "Turnaje v Balabušce", every Wednesday) —
 * deliberately a separate model from `Tournament`, not a "recurring" flag on it. The two don't
 * share a natural shape: `Tournament` is date-driven (start_date/end_date, category, badge,
 * ranked by soonest-first) and grows without bound as events pass, while these have a fixed
 * `frequency` text instead of a date, no category/badge, and stay a small, stable list — mixing
 * them would mean a pile of nullable columns only relevant to one side, and these would get
 * lost admin-side among an ever-growing Tournament table. See skills/web-component-guide.md.
 */
class RecurringTournament extends Model
{
    /** @use HasFactory<RecurringTournamentFactory> */
    use HasFactory;

    use HasSlug;
    use HasTranslatableFormFields;

    public array $translatable = ['title', 'frequency', 'location_text', 'description', 'seo_title', 'seo_description'];

    protected $fillable = [
        'title',
        'title_translations',
        'slug_cs',
        'slug_en',
        'frequency',
        'frequency_translations',
        'location_text',
        'location_text_translations',
        'herna_id',
        'url',
        'description',
        'description_translations',
        'sort_order',
        'seo_title',
        'seo_title_translations',
        'seo_description',
        'seo_description_translations',
        'seo_image',
    ];

    protected static function slugSourceField(): string
    {
        return 'title';
    }

    public function herna(): BelongsTo
    {
        return $this->belongsTo(Herna::class);
    }

    protected function seoImageUrl(): Attribute
    {
        return Attribute::get(fn () => Seo::imageUrl($this->seo_image));
    }
}
