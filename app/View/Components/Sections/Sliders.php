<?php

namespace App\View\Components\Sections;

use App\Models\Slide;
use App\Services\SlideService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Sliders extends Component
{
    /**
     * Create a new component instance.
     */

    public Collection $slides;

    public function __construct(SlideService $slideService)
    {
        $this->slides = Slide::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.sliders');
    }
}
