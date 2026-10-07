<?php

namespace App\Http\Controllers;

use App\Services\PageService;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function __construct(
        private PageService $pageService
    ) {}

    public function show(string $name): View
    {
        $page = $this->pageService->findMainByNameOrFail($name);

        $sections = $this->pageService->sections($page);

        return view("pages.main", compact('page', 'sections'));
    }
}
