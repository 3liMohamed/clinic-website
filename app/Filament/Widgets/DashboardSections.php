<?php

namespace App\Filament\Widgets;

use App\Models;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardSections extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $models = [
            'slides' => Models\Slide::class,
            'clinic_infos' => Models\ClinicInfo::class,
            'features' => Models\Feature::class,
            'statistics' => Models\Statistic::class,
            'why_chooses' => Models\WhyChoose::class,
            'call_actions' => Models\CallAction::class,
        ];

        $stats = [];

        foreach ($models as $key => $model) {
            $stats[$key] = $model::query()
                ->selectRaw('
                    COUNT(*) as total,
                    COALESCE(
                        SUM(CASE WHEN is_visible = 1 THEN 1 ELSE 0 END),
                        0
                    ) as visible
                ')
                ->first();
        }

        return [
            Stat::make(
                __('system.dashboard.slides'),
                (int) $stats['slides']->total
            )
                ->description(
                    __('system.dashboard.visible') . ': ' . (int) $stats['slides']->visible
                )
                ->icon(Heroicon::OutlinedPhoto)
                ->color('primary'),

            Stat::make(
                __('system.dashboard.clinic_infos'),
                (int) $stats['clinic_infos']->total
            )
                ->description(
                    __('system.dashboard.visible') . ': ' . (int) $stats['clinic_infos']->visible
                )
                ->icon(Heroicon::OutlinedBuildingOffice2)
                ->color('info'),

            Stat::make(
                __('system.dashboard.features'),
                (int) $stats['features']->total
            )
                ->description(
                    __('system.dashboard.visible') . ': ' . (int) $stats['features']->visible
                )
                ->icon(Heroicon::OutlinedSparkles)
                ->color('success'),

            Stat::make(
                __('system.dashboard.statistics'),
                (int) $stats['statistics']->total
            )
                ->description(
                    __('system.dashboard.visible') . ': ' . (int) $stats['statistics']->visible
                )
                ->icon(Heroicon::OutlinedChartBar)
                ->color('info'),

            Stat::make(
                __('system.dashboard.why_chooses'),
                (int) $stats['why_chooses']->total
            )
                ->description(
                    __('system.dashboard.visible') . ': ' . (int) $stats['why_chooses']->visible
                )
                ->icon(Heroicon::OutlinedStar)
                ->color('warning'),

            Stat::make(
                __('system.dashboard.call_actions'),
                (int) $stats['call_actions']->total
            )
                ->description(
                    __('system.dashboard.visible') . ': ' . (int) $stats['call_actions']->visible
                )
                ->icon(Heroicon::OutlinedCursorArrowRays)
                ->color('primary'),
        ];
    }
}
