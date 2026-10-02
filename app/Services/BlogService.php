<?php

namespace App\Services;

use App\Contracts\HasDetails;
use App\Models\Blog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BlogService extends BaseService implements HasDetails
{
    protected const CACHE_KEY = 'blogs:all-visible';

    protected const MAX_HOME = 3;

    public function __construct(
        protected DetailPageService $detailPageService,
    ) {}

    public function allVisible(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Blog::query()
                ->visible()
                ->published()
                ->ordered()
                ->get();
        });
    }

    public function paginate(int $perPage = 6): LengthAwarePaginator
    {
        $blogs = $this->allVisible();

        $page = request()->integer('page', 1);

        return new LengthAwarePaginator(
            $blogs->forPage($page, $perPage)->values(),
            $blogs->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ],
        );
    }

    public function previous(Blog $blog): ?Blog
    {
        return $this->allVisible()
            ->where('has_detail_page', true)
            ->where('order', '<', $blog->order)
            ->sortByDesc('order')
            ->first();
    }

    public function next(Blog $blog): ?Blog
    {
        return $this->allVisible()
            ->where('has_detail_page', true)
            ->where('order', '>', $blog->order)
            ->sortBy('order')
            ->first();
    }

    public function home(): Collection
    {
        return $this->allVisible()->where('is_home', true);
    }

    public function findBySlugOrFail(string $slug): array
    {
        $id = (int) Str::before($slug, '-');

        $blog = $this->allVisible()->firstWhere('id', $id);

        abort_unless($blog, 404);
        abort_unless($blog->has_detail_page && $blog->slug === $slug, 404);

        $blog->load('user');

        $blog->setAttribute('previous', $this->previous($blog));
        $blog->setAttribute('next', $this->next($blog));

        return $this->detailPageService->resolve(
            'blog details',
            [
                'entity' => $blog,
                'meta' => [
                    'title' => $blog->title,
                    'description' => $blog->meta_description,
                    'keywords' => $blog->meta_keywords,
                    'image' => $blog->image_url,
                ],
            ],
        );
    }

    public function toggleHome(Blog $doctor, bool $visible): bool
    {
        if ($visible && $this->homeCount(excluding: $doctor) >= self::MAX_HOME) {
            return false;
        }

        $doctor->update(['is_home' => $visible]);

        return true;
    }

    public function homeCount(?Blog $excluding = null): int
    {
        return $this->allVisible()
            ->where('is_home', true)
            ->when(
                $excluding,
                fn($collection) => $collection->reject(
                    fn(Blog $item) => $item->is($excluding)
                )
            )
            ->count();
    }

    public function maxHome(): int
    {
        return self::MAX_HOME;
    }

    public function cacheKey(): string
    {
        return self::CACHE_KEY;
    }
}
