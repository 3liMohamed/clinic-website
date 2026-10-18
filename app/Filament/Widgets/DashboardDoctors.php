<?php

namespace App\Filament\Widgets;

use App\Models;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardDoctors extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $doctors = Models\Doctor::query()
            ->selectRaw('
                COUNT(*) as total,
                COALESCE(SUM(CASE WHEN is_visible = 1 THEN 1 ELSE 0 END), 0) as visible
            ')
            ->first();

        $departments = Models\Department::query()
            ->selectRaw('
                COUNT(*) as total,
                COALESCE(SUM(CASE WHEN is_visible = 1 THEN 1 ELSE 0 END), 0) as visible
            ')
            ->first();

        $pricing = Models\Pricing::query()
            ->selectRaw('
                COUNT(*) as total,
                COALESCE(SUM(CASE WHEN is_visible = 1 THEN 1 ELSE 0 END), 0) as visible
            ')
            ->first();

        return [
            Stat::make(
                __('system.dashboard.doctor'),
                (int) $doctors->total
            )
                ->description(
                    __('system.dashboard.visible').': '.(int) $doctors->visible
                )
                ->icon(Heroicon::OutlinedUserGroup)
                ->color('primary'),

            Stat::make(
                __('system.dashboard.department'),
                (int) $departments->total
            )
                ->description(
                    __('system.dashboard.visible').': '.(int) $departments->visible
                )
                ->icon(Heroicon::OutlinedBuildingOffice2)
                ->color('info'),

            Stat::make(
                __('system.dashboard.pricing'),
                (int) $pricing->total
            )
                ->description(
                    __('system.dashboard.visible').': '.(int) $pricing->visible
                )
                ->icon(Heroicon::OutlinedCurrencyDollar)
                ->color('success'),
        ];
    }
}
