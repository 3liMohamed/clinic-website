<?php

namespace App\View\Components\Sections;

use App\Services\FeatureService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Features extends Component
{
    /**
     * Create a new component instance.
     */

    public ?Collection $features;

    public function __construct(FeatureService $featureService)
    {
        $this->features = $featureService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.features');
    }
}
