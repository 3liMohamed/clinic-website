<?php

namespace App\View\Components\Layouts;

use App\Services\PageService;
use App\Services\SettingService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * Create a new component instance.
     */
    public array $workingDays;
    public Collection $footerPages;

    public function __construct(SettingService $settingService, PageService $pageService)
    {

         $workingDays = $settingService->get('working_days', []);

        if (is_string($workingDays)) {
            $workingDays = json_decode($workingDays, true) ?? [];
        }

        $this->workingDays = is_array($workingDays) ? $workingDays : [];

        $this->footerPages = $pageService->allVisibleFooter();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.footer');
    }
}
