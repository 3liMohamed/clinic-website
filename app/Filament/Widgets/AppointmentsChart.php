<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class AppointmentsChart extends ChartWidget
{
    use HasFiltersSchema;

    public function getHeading(): string
    {
        return __('system.dashboard.appointments');
    }

    protected int|string|array $columnSpan = 'full';
    protected ?string $maxHeight = '280px';

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([

            Select::make('group_by')
                ->options([
                    'day' => __('system.dashboard.day'),
                    'week' => __('system.dashboard.week'),
                    'month' => __('system.dashboard.month'),
                    'year' => __('system.dashboard.year'),
                ])
                ->default('day')
                ->label(__('system.dashboard.group_by'))
                ->live(),
        ]);
    }

    protected function getData(): array
    {
        $startDate = filled($this->filters['startDate'] ?? null)
            ? Carbon::parse($this->filters['startDate'])->startOfDay()
            : now()->startOfYear();

        $endDate = filled($this->filters['endDate'] ?? null)
            ? Carbon::parse($this->filters['endDate'])->endOfDay()
            : now()->endOfDay();

        $groupBy = $this->filters['group_by'] ?? 'day';

        $format = match ($groupBy) {
            'week' => '%Y-%W',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m-%d',
        };

        $appointments = Appointment::query()
            ->whereBetween('requested_at', [$startDate, $endDate])
            ->selectRaw("strftime('$format', requested_at) as period, COUNT(*) as total")
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period');

        $labels = [];
        $data = [];

        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {
            $key = match ($groupBy) {
                'week' => $cursor->format('Y-W'),
                'month' => $cursor->format('Y-m'),
                'year' => $cursor->format('Y'),
                default => $cursor->format('Y-m-d'),
            };

            $labels[] = match ($groupBy) {
                'week' => $cursor->format('W/Y'),
                'month' => $cursor->format('m/Y'),
                'year' => $cursor->format('Y'),
                default => $cursor->format('d/m'),
            };

            $data[] = (int) ($appointments[$key] ?? 0);

            match ($groupBy) {
                'week' => $cursor->startOfWeek()->addWeek(),
                'month' => $cursor->startOfMonth()->addMonth(),
                'year' => $cursor->startOfYear()->addYear(),
                default => $cursor->addDay(),
            };
        }

        return [
            'datasets' => [
                [
                    'label' => __('system.dashboard.appointments'),
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
