<?php

namespace App\View\Components\Sections;

use App\Services\SettingService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Contact extends Component
{
    /**
     * Create a new component instance.
     */
    public ?array $location;

    public function __construct(SettingService $settingService)
    {
        $location = $settingService->get('location');

        $this->location = is_string($location)
            ? json_decode($location, true)
            : $location;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.contact');
    }
}
