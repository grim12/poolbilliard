<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use App\Settings\GeneralSettings;
use Database\Factories\TournamentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tournament extends Model
{
    /** @use HasFactory<TournamentFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['title', 'location_text'];

    protected $fillable = [
        'title',
        'title_translations',
        'url',
        'tournament_category_id',
        'start_date',
        'end_date',
        'location_text',
        'location_text_translations',
        'badge',
        'sort_order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TournamentCategory::class, 'tournament_category_id');
    }

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'badge' => 'boolean',
        ];
    }

    /**
     * Computed, not stored — true when start_date falls within the admin-configurable
     * "soon" window (Settings > Obecné nastavení, GeneralSettings::$tournament_soon_threshold_days).
     * Replaces the old manual `soon` toggle, so a highlighted date always reflects the real
     * date instead of a flag someone has to remember to update.
     */
    protected function soon(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->start_date) {
                return false;
            }

            $thresholdDays = app(GeneralSettings::class)->tournament_soon_threshold_days;

            return $this->start_date->between(now(), now()->addDays($thresholdDays));
        });
    }

    /**
     * Computed, not stored — Czech-formatted date/date-range for display, replaces the old
     * manually-typed `date_text` (same "computed over manual" reasoning as soon(): a hand-typed
     * text field can drift from start_date/end_date, this can't). Carbon's "cs" locale already
     * outputs the correct genitive month form ("20. srpna 2026"), no custom month table needed.
     */
    protected function dateText(): Attribute
    {
        return Attribute::get(function () {
            $start = $this->start_date;
            $end = $this->end_date;

            if (! $start) {
                return 'Termín bude upřesněn';
            }

            if (! $end || $end->equalTo($start)) {
                return $start->locale('cs')->translatedFormat('j. F Y');
            }

            if ($start->year !== $end->year) {
                return $start->locale('cs')->translatedFormat('j. F Y').' – '.$end->locale('cs')->translatedFormat('j. F Y');
            }

            if ($start->month !== $end->month) {
                return $start->locale('cs')->translatedFormat('j. F').' – '.$end->locale('cs')->translatedFormat('j. F Y');
            }

            return $start->locale('cs')->translatedFormat('j.').' – '.$end->locale('cs')->translatedFormat('j. F Y');
        });
    }

    /**
     * Tournaments that haven't ended yet — "ended" means their effective end date
     * (end_date, or start_date when there's no end_date) is in the past. Tournaments with no
     * date set at all (TBD) are always included, since there's nothing to say they're over.
     */
    public function scopeCurrentAndUpcoming(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where(function (Builder $q) use ($today) {
            $q->whereRaw('COALESCE(end_date, start_date) >= ?', [$today])
                ->orWhere(function (Builder $q2) {
                    $q2->whereNull('start_date')->whereNull('end_date');
                });
        });
    }

    /**
     * Soonest first, `sort_order` only breaks ties on the same date. Tournaments with no
     * start_date at all (TBD) sort last, since there's no real date to compare — `IS NULL`
     * evaluates to 0/1 in both SQLite and MySQL, so ascending puts non-null dates first.
     */
    public function scopeOrderedByStartDate(Builder $query): Builder
    {
        return $query->orderByRaw('start_date IS NULL')
            ->orderBy('start_date')
            ->orderBy('sort_order');
    }
}
