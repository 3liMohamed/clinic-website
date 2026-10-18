<?php

namespace App\Providers;

use App\Services;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Services\PageService::class);
        $this->app->singleton(Services\DetailResolver::class);
        $this->app->singleton(Services\DetailPageService::class);

        $this->app->singleton(Services\SettingService::class);
        $this->app->singleton(Services\WebsiteGalleryService::class);
        $this->app->singleton(Services\TranslationService::class);

        $this->app->singleton(Services\SlideService::class);
        $this->app->singleton(Services\ClinicInfoService::class);
        $this->app->singleton(Services\FeatureService::class);
        $this->app->singleton(Services\StatisticService::class);
        $this->app->singleton(Services\WhyChooseService::class);
        $this->app->singleton(Services\CallActionService::class);
        $this->app->singleton(Services\PortfolioService::class);
        $this->app->singleton(Services\ServiceService::class);
        $this->app->singleton(Services\TestimonialService::class);
        $this->app->singleton(Services\DepartmentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->directives();

        FilamentColor::register([
            'red' => Color::Red,
            'orange' => Color::Orange,
            'amber' => Color::Amber,
            'yellow' => Color::Yellow,
            'lime' => Color::Lime,
            'green' => Color::Green,
            'emerald' => Color::Emerald,
            'teal' => Color::Teal,
            'cyan' => Color::Cyan,
            'sky' => Color::Sky,
            'blue' => Color::Blue,
            'indigo' => Color::Indigo,
            'violet' => Color::Violet,
            'purple' => Color::Purple,
            'fuchsia' => Color::Fuchsia,
            'pink' => Color::Pink,
            'rose' => Color::Rose,
            'slate' => Color::Slate,
            'zinc' => Color::Zinc,
            'neutral' => Color::Neutral,
            'stone' => Color::Stone,
        ]);

        RateLimiter::for('subscribe', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        RateLimiter::for('appointment', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        RateLimiter::for('contact', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });
    }

    private function directives()
    {
        Blade::directive('t', function ($expression) {
            return "<?php echo app('" . Services\TranslationService::class . "')->get({$expression}); ?>";
        });

        Blade::directive('setting', function ($expression) {
            return "<?php echo app(\App\Services\SettingService::class)->get($expression); ?>";
        });

        Blade::if('settingAvailable', function (string $key): bool {
            $value = app(Services\SettingService::class)->get($key);

            return $value !== null && $value !== '' && $value !== '#';
        });

        Blade::directive('settingAsset', function ($expression) {
            return "<?php echo asset('images/' . app('" . Services\SettingService::class . "')->get($expression)); ?>";
        });

        Blade::directive('asset', function ($expression) {
            return "<?php echo asset($expression); ?>";
        });

        Blade::directive('route', function ($expression) {
            return "<?php echo route($expression); ?>";
        });

        Blade::directive('galleryImage', function ($expression) {
            return "<?php echo (app('" . Services\WebsiteGalleryService::class . "')->getByKey({$expression}))?->image_url; ?>";
        });

        Blade::directive('galleryTitle', function ($expression) {
            return "<?php echo (app('" . Services\WebsiteGalleryService::class . "')->getByKey({$expression}))?->title; ?>";
        });
    }
}
