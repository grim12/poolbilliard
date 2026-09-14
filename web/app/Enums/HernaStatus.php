<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Moderation status for the public "Registrace herny" submission workflow (ui/'s form submits
 * with "Odeslat ke schválení" — for approval). The public submission form/route itself isn't
 * built yet (see skills/web-component-guide.md) — this just makes the model ready for it.
 * Admin-created/seeded herny default to Approved (already vetted), a future public submission
 * would default to Pending.
 */
enum HernaStatus: string implements HasColor, HasLabel
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Čeká na schválení',
            self::Approved => 'Schváleno',
            self::Rejected => 'Zamítnuto',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }
}
