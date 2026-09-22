<?php

namespace App\View\Components\Sections;

use App\Services\StatisticService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Statistics extends Component
{
    /**
     * Create a new component instance.
     */

    public ?Collection $statistics;

    public function __construct(StatisticService $statisticService)
    {
        $this->statistics = $statisticService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.statistics');
    }
}
