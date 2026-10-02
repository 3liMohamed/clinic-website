<?php

namespace App\View\Components\Sections;

use App\Models\Page;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public function __construct(public Page $page) {
        $this->page->load('breadcrumbItems');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.breadcrumbs');
    }
}
