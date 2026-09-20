<?php

namespace App\View\Components\Layouts;

use App\Models\Page;
use App\Services\PageService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public ?Page $page;

    public function __construct(PageService $pageService)
    {
        $this->page = $pageService->findByName('home');
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.header');
    }
}
