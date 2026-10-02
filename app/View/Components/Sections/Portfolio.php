<?php

namespace App\View\Components\Sections;

use App\Services\PortfolioService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Portfolio extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Collection $portfolios;
    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolios = $portfolioService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.portfolio');
    }
}
