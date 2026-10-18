<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardPages extends StatsOverviewWidget
{
    protected int|array|null $columns = 1;

    protected function getStats(): array
    {
        $stats = Page::query()
            ->selectRaw('
                COUNT(*) as total,
                COALESCE(SUM(CASE WHEN is_visible = 1 THEN 1 ELSE 0 END), 0) as visible
            ')
            ->first();

        $total = (int) $stats->total;
        $visible = (int) $stats->visible;

        return [
            Stat::make(
                __('system.dashboard.pages'),
                $total
            )
                ->description(
                    __('system.dashboard.visible') . ': ' . $visible
                )
                ->icon(Heroicon::OutlinedDocumentText)
                ->color('primary'),
        ];
    }
}
