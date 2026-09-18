<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Fixed, small set of disciplines a herna can offer (checkbox pills in ui/'s registration
 * form) — not an admin-manageable taxonomy, stored as a plain JSON array on Herna::$sports.
 */
enum Sport: string implements HasLabel
{
    case Poolbilliard = 'Poolbilliard';
    case Karambol = 'Karambol';
    case Snooker = 'Snooker';
    case Pyramida = 'Pyramida';
    case Heyball = 'Heyball';

    public function getLabel(): string
    {
        if (app()->getLocale() === 'en') {
            return match ($this) {
                self::Poolbilliard => 'Pool',
                self::Karambol => 'Carom',
                self::Snooker => 'Snooker',
                self::Pyramida => 'Pyramid',
                self::Heyball => 'Heyball',
            };
        }

        return $this->value;
    }
}
