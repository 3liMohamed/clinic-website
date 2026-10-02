<?php

namespace App\View\Components\Sections;

use App\Services\TestimonialService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Testimonials extends Component
{

    public ?Collection $testimonials;

    public function __construct(TestimonialService $testimonialService)
    {
        $this->testimonials = $testimonialService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.testimonials');
    }
}
