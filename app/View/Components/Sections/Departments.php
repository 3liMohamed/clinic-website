<?php

namespace App\View\Components\Sections;

use App\Services\DepartmentService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Departments extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Collection $departments;
    public function __construct(DepartmentService $departmentService)
    {
        $this->departments = $departmentService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.departments');
    }
}
