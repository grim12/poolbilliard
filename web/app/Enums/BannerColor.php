<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Matches ui/'s button()/tag() macro `color` values that Banner actually needs — the shared
 * default color applied to the banner's tag and buttons unless a button overrides it. Button
 * itself only supports primary/accent/dark (tag.njk has more, e.g. gray/gold, but Banner never
 * uses those), so this is the narrower set.
 */
enum BannerColor: string implements HasColor, HasLabel
{
    case Primary = 'primary';
    case Accent = 'accent';
    case Dark = 'dark';

    public function getLabel(): string
    {
        return match ($this) {
            self::Primary => 'Primary',
            self::Accent => 'Accent',
            self::Dark => 'Dark',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Primary => 'primary',
            self::Accent => 'danger',
            self::Dark => 'gray',
        };
    }
}
