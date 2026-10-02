<?php

namespace App\View\Components\Sections;

use App\Services\PricingService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Pricings extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Collection $pricings;

    public function __construct(PricingService $pricingService)
    {
        $this->pricings = $pricingService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.pricings');
    }
}
