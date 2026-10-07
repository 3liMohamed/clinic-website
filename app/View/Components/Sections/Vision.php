<?php

namespace App\View\Components\Sections;

use App\Services\VisionService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Vision extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Collection $visions;

    public function __construct(VisionService $visionService)
    {
        $this->visions = $visionService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.vision');
    }
}
