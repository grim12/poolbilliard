<?php

namespace App\Models;

use App\Settings\GeneralSettings;
use Database\Factories\TournamentFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    /** @use HasFactory<TournamentFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'tag_text',
        'tag_color',
        'date_text',
        'start_date',
        'location_text',
        'badge',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
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
}
