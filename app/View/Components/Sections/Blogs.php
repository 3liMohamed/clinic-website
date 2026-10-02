<?php

namespace App\View\Components\Sections;

use App\Services\BlogService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Blogs extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Collection $blogs;

    public function __construct(BlogService $blogService)
    {
        $this->blogs = $blogService->home();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.blogs');
    }
}
