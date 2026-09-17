<?php

namespace App\Filament\Support;

use App\Support\InternalLink;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

/**
 * The "Interní stránka" / "Konkrétní záznam" picker every linkable model's Filament form adds
 * next to its existing manual URL field (see App\Support\InternalLink, which resolves whichever
 * one an admin picked into a real cs/en URL at render time — a route/record pick always wins
 * over a manually typed URL left over from before).
 *
 * $prefix lets a form add this twice for two independent link targets (e.g. JakZacitSectionForm's
 * "aside_panel_"/"aside_card_" buttons) without their fields colliding — it must match the
 * column prefix used in that model's migration (`{$prefix}link_route`, `{$prefix}linkable_type`,
 * `{$prefix}linkable_id`).
 */
class InternalLinkFields
{
    /**
     * @return array<Component>
     */
    public static function make(string $prefix = ''): array
    {
        return [
            Select::make("{$prefix}link_route")
                ->label('Interní stránka')
                ->options(InternalLink::routeOptions())
                ->native(false)
                ->helperText('Vybraná stránka má přednost před ručně zadanou URL i před záznamem níže. Stejná volba vede na správnou URL v CZ i EN verzi webu.'),
            Select::make("{$prefix}linkable_type")
                ->label('Nebo konkrétní záznam — typ')
                ->options(InternalLink::linkableTypes())
                ->native(false)
                ->live()
                ->afterStateUpdated(fn (Set $set) => $set("{$prefix}linkable_id", null)),
            Select::make("{$prefix}linkable_id")
                ->label('Nebo konkrétní záznam — položka')
                ->options(fn (Get $get) => $get("{$prefix}linkable_type")
                    ? InternalLink::recordOptions($get("{$prefix}linkable_type"))
                    : [])
                ->native(false)
                ->searchable(),
        ];
    }
}
