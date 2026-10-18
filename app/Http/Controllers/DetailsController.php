<?php

namespace App\Http\Controllers;

use App\Services\DetailResolver;
use Illuminate\Contracts\View\View;

class DetailsController extends Controller
{
    public function __construct(
        private DetailResolver $detailResolver,
    ) {}

    public function __invoke(string $slug, string $type)
    {
        $service = $this->detailResolver->resolve($type);
        $detail = $service->findBySlugOrFail($slug);

        return view($detail['view'], $detail['data']);
    }
}
