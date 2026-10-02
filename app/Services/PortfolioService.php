<?php

namespace App\Services;

use App\Contracts\HasDetails;
use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PortfolioService extends BaseService implements HasDetails
{
    protected const CACHE_KEY = 'portfolios:all-visible';

    public function __construct(
        protected DetailPageService $detailPageService,
    ) {}

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Portfolio::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    public function findBySlugOrFail(string $slug): array
    {
        $id = (int) Str::before($slug, '-');

        $portfolio = $this->allVisible()->firstWhere('id', $id);

        abort_unless($portfolio, 404);
        abort_unless($portfolio->has_detail_page && $portfolio->slug === $slug, 404);

        return $this->detailPageService->resolve(
            'portfolio details',
            [
                'entity' => $portfolio,
                'meta' => [
                    'title' => $portfolio->title,
                    'description' => $portfolio->meta_description,
                    'keywords' => $portfolio->meta_keywords,
                    'image' => $portfolio->cover_image_url,
                ],
            ],
        );
    }

    protected function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
