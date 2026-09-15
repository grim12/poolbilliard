<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\CompetitionSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * One numbered content block on /souteze (Regiony, Česká poolová tour, MČR jednotlivců, ...) —
 * ui/'s contentSection() macro called 6× with different data (see souteze.njk). Deliberately
 * named CompetitionSection, not Competition — this models a *page section*, not the eventual
 * standalone competition/series entity the user expects to build later (see
 * skills/web-component-guide.md), so the name won't collide once that's a real model.
 *
 * `body`/`aside`/`below` are freeform RichEditor HTML — ui/'s mock hand-authors bespoke layout
 * inside these slots (a 2-column stat breakdown, a tile grid, a colored infoPanel() card), none
 * of which a RichEditor can reproduce (it only outputs basic rich text: paragraphs, bold,
 * lists, links). That visual richness is intentionally simplified to plain rich text in a
 * generic `.c-section__card` box (or no box, for `body`) — a deliberate tradeoff, not an
 * oversight: this page is explicitly a temporary hub (see the same doc), so building bespoke
 * structured fields for a layout that's expected to be rebuilt per-competition later isn't
 * worth it.
 *
 * `anchor` is NOT an auto-generated slug (no HasSlug) — it's a same-page `#hash` for jumpNav,
 * hand-picked to be short/memorable (e.g. "cpt" for Česká poolová tour), not mechanically
 * derived from the title.
 */
class CompetitionSection extends Model
{
    /** @use HasFactory<CompetitionSectionFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['nav_label', 'eyebrow', 'title', 'body', 'aside', 'below'];

    protected $fillable = [
        'anchor',
        'nav_label',
        'nav_label_translations',
        'eyebrow',
        'eyebrow_translations',
        'title',
        'title_translations',
        'body',
        'body_translations',
        'aside',
        'aside_translations',
        'below',
        'below_translations',
        'sort_order',
    ];
}
