<?php

namespace App\Providers;

use App\Services;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Services\SettingService::class);
        $this->app->singleton(Services\WebsiteGalleryService::class);
        $this->app->singleton(Services\PageService::class);
        $this->app->singleton(Services\TranslationService::class);
        $this->app->singleton(Services\SlideService::class);
        $this->app->singleton(Services\ClinicInfoService::class);
        $this->app->singleton(Services\FeatureService::class);
        $this->app->singleton(Services\StatisticService::class);
        $this->app->singleton(Services\WhyChooseService::class);
        $this->app->singleton(Services\CallActionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->directives();
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
