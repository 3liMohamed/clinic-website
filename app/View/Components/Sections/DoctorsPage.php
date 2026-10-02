<?php

namespace App\View\Components\Sections;

use App\Services\DoctorService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class DoctorsPage extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Collection $doctors;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctors = $doctorService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.doctors-page');
    }
}
