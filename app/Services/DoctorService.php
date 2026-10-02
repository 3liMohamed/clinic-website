<?php

namespace App\Services;

use App\Contracts\HasDetails;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DoctorService extends BaseService implements HasDetails
{
    protected const CACHE_KEY = 'doctors:all-visible';
    protected const MAX_HOME = 4;

    public function __construct(
        protected DetailPageService $detailPageService,
    ) {}

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Doctor::query()
                ->visible()
                ->ordered()
                ->with([
                    'department',
                    'workingHours' => fn($query) => $query
                        ->where('is_visible', true)
                        ->orderBy('day'),
                ])
                ->get();
        });
    }

    public function findBySlugOrFail(string $slug): array
    {
        $id = (int) Str::before($slug, '-');

        $doctor = $this->allVisible()->firstWhere('id', $id);

        abort_unless($doctor, 404);
        abort_unless($doctor->has_detail_page && $doctor->slug === $slug, 404);

        return $this->detailPageService->resolve(
            'doctor details',
            [
                'entity' => $doctor,
                'meta' => [
                    'title' => $doctor->name,
                    'description' => $doctor->meta_description,
                    'keywords' => $doctor->meta_keywords,
                    'image' => $doctor->image_url,
                ],
            ],
        );
    }


    public function toggleHome(Doctor $doctor, bool $visible): bool
    {
        if ($visible && $this->homeCount(excluding: $doctor) >= self::MAX_HOME) {
            return false;
        }

        $doctor->update(['is_home' => $visible]);

        return true;
    }

    public function homeCount(?Doctor $excluding = null): int
    {
        return $this->allVisible()
            ->where('is_home', true)
            ->when(
                $excluding,
                fn($collection) => $collection->reject(
                    fn(Doctor $item) => $item->is($excluding)
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
