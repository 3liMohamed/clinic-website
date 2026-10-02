<?php

namespace App\View\Components\Layouts;

use App\Models\Page;
use App\Services\PageService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Header extends Component
{
    public ?Collection $mainNavPages;
    public ?Collection $topNavPages;
    public ?Page $ctaPage;

    public function __construct(PageService $pageService)
    {
        $pages = $pageService->allVisibleMain();
        $this->mainNavPages = $pages->filter->isInMainNav()->values();
        $this->topNavPages = $pages->filter->isInTopNav()->values();
        $this->ctaPage = $pages->first->isCta();
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.header');
    }
}
