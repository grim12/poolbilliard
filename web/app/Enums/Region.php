<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * The 14 Czech kraje — a closed, geography-defined list that never changes, so a backed enum
 * (not an admin-manageable taxonomy table like TournamentCategory/FaqGroup). Shared between
 * Club and Herna. Values are the exact display strings already used in ui/'s mock data.
 */
enum Region: string implements HasLabel
{
    case Praha = 'Praha';
    case Stredocesky = 'Středočeský';
    case Jihocesky = 'Jihočeský';
    case Plzensky = 'Plzeňský';
    case Karlovarsky = 'Karlovarský';
    case Ustecky = 'Ústecký';
    case Liberecky = 'Liberecký';
    case Kralovehradecky = 'Královéhradecký';
    case Pardubicky = 'Pardubický';
    case Vysocina = 'Vysočina';
    case Jihomoravsky = 'Jihomoravský';
    case Olomoucky = 'Olomoucký';
    case Zlinsky = 'Zlínský';
    case Moravskoslezsky = 'Moravskoslezský';

    public function getLabel(): string
    {
        return $this->value;
    }
}
