<?php

namespace App\View\Components\Sections;

use App\Services\BlogService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class BlogsPage extends Component
{
    /**
     * Create a new component instance.
     */
    public LengthAwarePaginator $blogs;

    public function __construct(BlogService $blogService)
    {
        $this->blogs = $blogService->paginate(9);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.blogs-page');
    }
}
