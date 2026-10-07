<?php

namespace App\View\Components\Sections;

use App\Models\AboutUs;
use App\Services\AboutUsService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class About extends Component
{
    /**
     * Create a new component instance.
     */
    public ?AboutUs $about;

    public function __construct(AboutUsService $aboutUsService)
    {
        $this->about = $aboutUsService->visible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.about');
    }
}
