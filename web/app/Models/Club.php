<?php

namespace App\Models;

use App\Enums\Region;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslatableFormFields;
use App\Settings\GeneralSettings;
use Database\Factories\ClubFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Club extends Model
{
    /** @use HasFactory<ClubFactory> */
    use HasFactory;

    use HasSlug;
    use HasTranslatableFormFields;

    public array $translatable = ['about_text', 'recruitment_text'];

    protected $fillable = [
        'name',
        'full_name',
        'slug_cs',
        'slug_en',
        'address',
        'city',
        'region',
        'image',
        'lat',
        'lng',
        'about_text',
        'about_text_translations',
        'ambassador_name',
        'ambassador_website',
        'recruitment_open',
        'recruitment_text',
        'recruitment_text_translations',
    ];

    protected function casts(): array
    {
        return [
            'region' => Region::class,
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'recruitment_open' => 'boolean',
        ];
    }

    public function members(): HasMany
    {
        return $this->hasMany(ClubMember::class)->orderBy('sort_order');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? Storage::disk('public')->url($this->image) : null);
    }

    /**
     * The recruitment_text an admin typed for this specific club, or — when that's empty — the
     * matching global fallback text for the current recruitment_open state (Settings > Obecné
     * nastavení). Same "computed over manual" pattern as Tournament::soon()/dateText(), except
     * here the per-record override is a real value that wins when present, not always computed.
     */
    protected function recruitmentMessage(): Attribute
    {
        return Attribute::get(function () {
            if ($this->recruitment_text) {
                return $this->recruitment_text;
            }

            $settings = app(GeneralSettings::class);

            return $this->recruitment_open
                ? $settings->recruitment_open_fallback_text
                : $settings->recruitment_closed_fallback_text;
        });
    }
}
