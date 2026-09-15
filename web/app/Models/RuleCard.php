<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\RuleCardFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * One "Pravidla podle disciplíny" card on /pravidla (Obecná pravidla, 8-ball, 9-ball...) —
 * mirrors macros/rule-card.njk. `icon` (a heroicon name) is used only when `image` is empty —
 * ui/'s macro also has a 3rd "balls" pictogram variant, dropped here since no page ever passes
 * it and it has no real content behind it (same reasoning as content-section.blade.php dropping
 * the unused `eyebrowColor="accent"` option). Which visual variant to render is computed from
 * whether `image`/`icon` are present, not a stored flag.
 */
class RuleCard extends Model
{
    /** @use HasFactory<RuleCardFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['title', 'subtitle', 'text'];

    protected $fillable = [
        'title',
        'title_translations',
        'subtitle',
        'subtitle_translations',
        'text',
        'text_translations',
        'icon',
        'image',
        'image_alt',
        'button_url',
        'sort_order',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? Storage::disk('public')->url($this->image) : null);
    }
}
