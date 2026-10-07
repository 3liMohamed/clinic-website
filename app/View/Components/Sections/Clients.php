<?php

namespace App\View\Components\Sections;

use App\Services\ClientService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Clients extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Collection $clients;

    public function __construct(ClientService $clientService)
    {
        $this->clients = $clientService->allVisible();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.clients');
    }
}
