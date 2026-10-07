<?php

namespace App\View\Components\Sections;

use App\Services\DoctorService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Appointment extends Component
{
    public ?Collection $doctors;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctors = $doctorService->allVisible();
    }

    public function render(): View|Closure|string
    {
        return view('components.sections.appointment');
    }
}
