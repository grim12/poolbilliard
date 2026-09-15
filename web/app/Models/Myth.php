<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\MythFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A myth-vs-fact item shown on /pravidla's <x-myth-faq> accordion. Its own entity (not a field
 * on PravidlaSettings) — currently only listed on this one page, but expected to potentially
 * appear elsewhere later, at which point a taxonomy (own model + belongsToMany, same shape as
 * FaqGroup/FaqItem) would decide placement. Deliberately no such grouping yet — see
 * skills/web-component-guide.md.
 */
class Myth extends Model
{
    /** @use HasFactory<MythFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['myth_text', 'correct_text'];

    protected $fillable = [
        'myth_text',
        'myth_text_translations',
        'correct_text',
        'correct_text_translations',
        'sort_order',
    ];
}
