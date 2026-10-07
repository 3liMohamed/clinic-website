<?php

namespace App\Services;

use App\Contracts\HasDetails;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ServiceService extends BaseService implements HasDetails
{
    protected const CACHE_KEY = 'services:all-visible';
    protected const MAX_HOME = 6;

    public function __construct(
        protected DetailPageService $detailPageService,
    ) {}

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Service::query()
                ->visible()
                ->ordered()
                ->get();
        });
    }

    public function findBySlugOrFail(string $slug): array
    {
        $id = (int) Str::before($slug, '-');

        $service = $this->allVisible()->firstWhere('id', $id);

        abort_unless($service, 404);
        abort_unless($service->has_detail_page && $service->slug === $slug, 404);

        return $this->detailPageService->resolve(
            'service details',
            [
                'entity' => $service,
                'meta' => [
                    'title' => $service->title,
                    'description' => $service->meta_description,
                    'keywords' => $service->meta_keywords,
                    'image' => $service->image_url,
                ],
            ],
        );
    }

    public function toggleHome(Service $service, bool $visible): bool
    {
        if ($visible && $this->homeCount(excluding: $service) >= self::MAX_HOME) {
            return false;
        }

        $service->update(['is_home' => $visible]);

        return true;
    }

    public function homeCount(?Service $excluding = null): int
    {
        return $this->allVisible()
            ->where('is_home', true)
            ->when(
                $excluding,
                fn($collection) => $collection->reject(
                    fn(Service $item) => $item->is($excluding)
                )
            )
            ->count();
    }

    public function maxHome(): int
    {
        return self::MAX_HOME;
    }

    protected function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
