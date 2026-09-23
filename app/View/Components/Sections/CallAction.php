<?php

namespace App\View\Components\Sections;

use App\Services\CallActionService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\CallAction as Model;

class CallAction extends Component
{
    /**
     * Create a new component instance.
     */

    public ?Model $callAction;

    public function __construct(CallActionService $callActionService)
    {
        $this->callAction = $callActionService->visible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.call-action');
    }
}
