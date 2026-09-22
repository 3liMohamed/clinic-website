<?php

namespace App\View\Components\Sections;

use App\Services\ClinicInfoService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class ClinicInfos extends Component
{
    /**
     * Create a new component instance.
     */

    public ?Collection $infos;

    public function __construct(ClinicInfoService $clinicInfoService)
    {
        $this->infos = $clinicInfoService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.clinic-infos');
    }
}
