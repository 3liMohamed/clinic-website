<?php

namespace App\View\Components\Sections;

use App\Services\WhyChooseService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\WhyChoose as WhyChooseModel;

class WhyChoose extends Component
{
    /**
     * Create a new component instance.
     */

    public ?WhyChooseModel $whyChoose;

    public function __construct(WhyChooseService $whyChoose)
    {
        $this->whyChoose = $whyChoose->visible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.why-choose');
    }
}
