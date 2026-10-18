<?php

namespace App\View\Components\Sections;

use App\Services\DoctorService;
use App\Services\SettingService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class AppointmentPage extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Collection $doctors;
    public array $workingDays;
    public function __construct(DoctorService $doctorService, SettingService $settingService)
    {
        $this->doctors = $doctorService->allVisible();

         $workingDays = $settingService->get('working_days', []);

        if (is_string($workingDays)) {
            $workingDays = json_decode($workingDays, true) ?? [];
        }

        $this->workingDays = is_array($workingDays) ? $workingDays : [];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.appointment-page');
    }
}
