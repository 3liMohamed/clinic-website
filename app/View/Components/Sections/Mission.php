<?php

namespace App\View\Components\Sections;

use App\Models\Mission as ModelsMission;
use App\Services\MissionService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Mission extends Component
{
    /**
     * Create a new component instance.
     */
    public ?ModelsMission $mission;
    public function __construct(MissionService $missionService)
    {
        $this->mission = $missionService->visible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.mission');
    }
}
