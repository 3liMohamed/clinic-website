<?php

namespace App\Contracts;

interface HasDetails
{
    public function findBySlugOrFail(string $slug): array;
}
