<?php

namespace App\Services;

use App\Contracts\HasDetails;

class DetailResolver
{
    public function resolve(string $type): HasDetails
    {
        return match ($type) {
            'portfolio' => app(PortfolioService::class),
            'service' => app(ServiceService::class),
            'doctor' => app(DoctorService::class),
            'blog' => app(BlogService::class),

            default => abort(404),
        };
    }
}
