<?php

namespace App\View\Components\Sections;

use App\Services\FaqService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Faqs extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Collection $faqs;

    public function __construct(FaqService $faqService)
    {
        $this->faqs = $faqService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.faqs');
    }
}
