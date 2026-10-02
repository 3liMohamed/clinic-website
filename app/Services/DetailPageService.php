<?php

namespace App\Services;

class DetailPageService
{
    public function __construct(
        protected PageService $pageService,
    ) {}

    public function resolve(string $pageName, array $data = []): array
    {
        $page = $this->pageService->findDetailsByName($pageName);

        return [
            'view' => 'pages.details',
            'data' => [
                'page' => $page,
                'meta' => $data['meta'] ?? [],
                'entity' => $data['entity'] ?? null,
                'sections' => $this->pageService->sections($page),
            ],
        ];
    }
}
