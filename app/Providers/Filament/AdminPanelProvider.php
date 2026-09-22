<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetLocale;
use App\Services\SettingService;
use Filament\Enums\ThemeMode;
use Filament\Forms\Components\Field;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Infolists\Components\Entry;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Schemas\Schema;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Martin6363\FilamentAiTranslator\FilamentAiTranslatorPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $settingService = app(SettingService::class);

        return $panel
            ->spa()
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()
            ->emailVerification()
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->brandName(fn() => $settingService->get('site:name', config('app.name')))
            ->favicon(fn() => asset('images/' . $settingService->get('logo:favicon', 'default.webp')))
            ->brandLogo(fn() => asset('images/' . $settingService->get('logo:light', 'default.webp')))
            ->darkModeBrandLogo(fn() => asset('images/' . $settingService->get('logo:dark', 'default.webp')))
            ->defaultThemeMode(ThemeMode::from($settingService->get('appearance:theme', ThemeMode::Light->value)))
            ->colors(fn(): array => [
                'primary' => Color::{$settingService->get('appearance:color')},
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->renderHook(PanelsRenderHook::USER_MENU_BEFORE, fn() => view('filament.components.locale-switcher'))
            ->renderHook(PanelsRenderHook::BODY_END, fn() => view('filament.components.footer'))
            ->assets([
                Css::make('icofont', asset('assets/css/icofont.css')),
                Css::make('font-awesome', asset('assets/css/font-awesome.min.css')),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(fn() => __('system.settings.group'))
                    ->icon(Heroicon::OutlinedCog6Tooth),
            ])
            ->plugins([
                SpatieTranslatablePlugin::make()
                    ->defaultLocales(['en', 'ar'])
                    ->persist(),
            ]);
    }

    public function boot(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table->defaultDateTimeDisplayFormat('Y-m-d h:i A');
            $table->defaultDateDisplayFormat('Y-m-d');
            $table->defaultTimeDisplayFormat('h:i A');
        });

        Column::configureUsing(function (Column $column): void {
            $column->placeholder(__('system.field.NA'));
        });

        Schema::configureUsing(function (Schema $schema): void {
            $schema->defaultDateTimeDisplayFormat('Y-m-d h:i A');
            $schema->defaultDateDisplayFormat('Y-m-d');
            $schema->defaultTimeDisplayFormat('h:i A');
        });

        Field::configureUsing(function (Field $field): void {
            if (method_exists($field, 'trim')) {
                $field->trim();
            }
        });

        Entry::configureUsing(function (Entry $entry): void {
            $entry->placeholder(__('system.field.NA'));
        });
    }
}
