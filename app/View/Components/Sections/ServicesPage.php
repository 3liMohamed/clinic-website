<?php

namespace App\View\Components\Sections;

use App\Services\ServiceService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class ServicesPage extends Component
{
    /**
     * Create a new component instance.
     */

    public ?Collection $services;

    public function __construct(ServiceService $serviceService)
    {
        $this->services = $serviceService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.services-page');
    }
}
