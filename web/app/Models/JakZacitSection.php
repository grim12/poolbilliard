<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use App\Support\InternalLink;
use Database\Factories\JakZacitSectionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * One "Kde začít" audience path on /jak-zacit (Úplný začátečník, Rekreační hráč, Rodič) —
 * mirrors the 3 {% call contentSection %} blocks in ui/src/jak-zacit.njk. Unlike
 * CompetitionSection's freeform aside/below RichEditor (accepted visual-fidelity tradeoff for
 * a page expected to be rebuilt later), this page's aside — a dark infoPanel() card + a small
 * link card — was asked to keep 1:1 visual fidelity, so both are structured fields here instead
 * of a rich-text blob.
 *
 * `steps` is a JSON repeater (icon/title/text, flat `_en` sibling per field, same convention as
 * KalendarSettings::$calendar_sources — no TranslatableTabs nested inside a Repeater) — each
 * step's `text` allows raw HTML (an embedded `<a>` link, same as ui/'s `| safe` filter), so it's
 * a RichEditor per row, not a plain Textarea.
 *
 * The FAQ block per section reuses the existing FaqItem/FaqGroup infrastructure — a FaqGroup
 * with `slug` equal to this record's `anchor` — instead of a new per-section FAQ field, same
 * principle as FaqController's "obecne" group lookup.
 */
class JakZacitSection extends Model
{
    /** @use HasFactory<JakZacitSectionFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = [
        'nav_label',
        'eyebrow',
        'title',
        'intro',
        'aside_panel_title',
        'aside_panel_text',
        'aside_panel_button_text',
        'aside_card_eyebrow',
        'aside_card_title',
        'aside_card_text',
        'aside_card_button_text',
        'faq_title',
    ];

    protected $fillable = [
        'anchor',
        'nav_label',
        'nav_label_translations',
        'eyebrow',
        'eyebrow_translations',
        'title',
        'title_translations',
        'intro',
        'intro_translations',
        'steps',
        'aside_panel_title',
        'aside_panel_title_translations',
        'aside_panel_text',
        'aside_panel_text_translations',
        'aside_panel_button_text',
        'aside_panel_button_text_translations',
        'aside_panel_button_url',
        'aside_panel_link_route',
        'aside_panel_linkable_type',
        'aside_panel_linkable_id',
        'aside_card_eyebrow',
        'aside_card_eyebrow_translations',
        'aside_card_title',
        'aside_card_title_translations',
        'aside_card_text',
        'aside_card_text_translations',
        'aside_card_button_text',
        'aside_card_button_text_translations',
        'aside_card_button_url',
        'aside_card_link_route',
        'aside_card_linkable_type',
        'aside_card_linkable_id',
        'faq_title',
        'faq_title_translations',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'steps' => 'array',
        ];
    }

    /**
     * @return Collection<int, FaqItem>
     */
    public function faqItems(): Collection
    {
        return FaqItem::whereHas('groups', fn ($query) => $query->where('slug', $this->anchor))
            ->orderBy('sort_order')
            ->get();
    }

    public function asidePanelLinkable(): MorphTo
    {
        return $this->morphTo('aside_panel_linkable', 'aside_panel_linkable_type', 'aside_panel_linkable_id');
    }

    public function asideCardLinkable(): MorphTo
    {
        return $this->morphTo('aside_card_linkable', 'aside_card_linkable_type', 'aside_card_linkable_id');
    }

    /**
     * The URL the aside panel's button should actually link to — see App\Support\InternalLink's
     * docblock for why link_route/linkable win over a manually typed `aside_panel_button_url`.
     */
    protected function asidePanelResolvedUrl(): Attribute
    {
        return Attribute::get(fn () => InternalLink::resolve($this->aside_panel_link_route, $this->asidePanelLinkable, $this->aside_panel_button_url));
    }

    protected function asideCardResolvedUrl(): Attribute
    {
        return Attribute::get(fn () => InternalLink::resolve($this->aside_card_link_route, $this->asideCardLinkable, $this->aside_card_button_url));
    }
}
