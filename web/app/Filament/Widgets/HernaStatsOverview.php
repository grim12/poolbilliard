<?php

namespace App\Filament\Widgets;

use App\Enums\HernaStatus;
use App\Filament\Resources\Hernas\HernaResource;
use App\Models\Herna;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Dashboard widget for the "Registrace herny" moderation queue — the number of herny still
 * waiting on approval is the thing an admin actually needs to see at a glance, with a direct
 * link into Hernas/Tables/HernasTable.php's already-filtered list (same `tableFilters` deep-link
 * mechanism Filament tables read from the URL automatically) instead of making them navigate
 * there and apply the filter by hand.
 */
class HernaStatsOverview extends StatsOverviewWidget
{
    /**
     * Filament widgets lazy-load by default (an initial placeholder, then a follow-up Livewire
     * request fills it in) — fine for something expensive, but this is two cheap COUNT queries
     * an admin should see the moment the dashboard opens, not after an extra round trip.
     */
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $pendingCount = Herna::where('status', HernaStatus::Pending)->count();

        return [
            Stat::make('Herny ke schválení', $pendingCount)
                ->description('Čekají na registraci z veřejného formuláře')
                ->descriptionIcon(Heroicon::Clock)
                ->color($pendingCount > 0 ? 'warning' : 'success')
                ->icon(Heroicon::BuildingStorefront)
                ->url(HernaResource::getUrl('index', [
                    'tableFilters' => ['status' => ['value' => HernaStatus::Pending->value]],
                ])),
            Stat::make('Schválené herny', Herna::where('status', HernaStatus::Approved)->count())
                ->description('Veřejně viditelné na /herny')
                ->color('success')
                ->icon(Heroicon::CheckCircle)
                ->url(HernaResource::getUrl('index', [
                    'tableFilters' => ['status' => ['value' => HernaStatus::Approved->value]],
                ])),
        ];
    }
}
