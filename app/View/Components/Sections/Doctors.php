<?php

namespace App\View\Components\Sections;

use App\Models\Doctor;
use App\Services\DoctorService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Doctors extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Collection $doctors;
    public function __construct(DoctorService $doctorService)
    {
        $this->doctors = $doctorService
            ->allVisible()
            ->where('is_home', true);

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.doctors');
    }
}
