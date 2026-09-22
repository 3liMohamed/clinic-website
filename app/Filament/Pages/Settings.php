<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Rules\ValidOrHash;
use App\Services\SettingService;
use BackedEnum;
use Filament\Enums\ThemeMode;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Settings extends Page
{
    use InteractsWithForms, AuthorizesRequests;

    protected string $view = 'filament.pages.settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;


    public static function getNavigationLabel(): string
    {
        return __('system.settings.navigation');
    }
    public function getHeading(): string
    {
        return __('system.settings.model');
    }
    public static function getNavigationGroup(): string
    {
        return __('system.settings.group');
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->can('viewAny', Setting::class) ?? false;
    }

    public static function canEdit(): bool
    {
        return Auth::user()?->can('updateSettings', Setting::class) ?? false;
    }

    public ?array $data = [];

    protected array $originalData = [];

    public function mount(SettingService $settingService): void
    {
        $this->authorize('viewAny', Setting::class);
        $this->originalData = $settingService->getAll();

        $this->form->fill($this->originalData);
    }

    public function save(SettingService $settingService): void
    {
        $this->authorize('updateSettings', Setting::class);
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if (($this->originalData[$key] ?? null) !== $value) {
                $settingService->set($key, $value);
            }
        }
        Notification::make()
            ->success()
            ->title(__('system.settings.saved'))
            ->send();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('settings')
                    ->heading(__('system.settings.title'))
                    ->disabled(fn(): bool => ! static::canEdit())
                    ->schema([

                        TextInput::make('site:name')
                            ->required()
                            ->maxLength(99)
                            ->columnSpan(12)
                            ->label(__('system.field.name')),

                        FileUpload::make('logo:favicon')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->disk('images')
                            ->directory('settings')
                            ->maxSize(2048)
                            ->columnSpan(4)
                            ->label(__('system.field.favicon')),

                        FileUpload::make('logo:light')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->disk('images')
                            ->directory('settings')
                            ->maxSize(2048)
                            ->columnSpan(4)
                            ->label(__('system.field.logo_light')),

                        FileUpload::make('logo:dark')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->disk('images')
                            ->directory('settings')
                            ->maxSize(2048)
                            ->columnSpan(4)
                            ->label(__('system.field.logo_dark')),

                        Select::make('appearance:language')
                            ->required()
                            ->options([
                                'ar' => __('locales.arabic'),
                                'en' => __('locales.english'),
                            ])
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->searchable()
                            ->columnSpan(4)
                            ->label(__('system.field.default_language')),

                        Select::make('appearance:theme')
                            ->required()
                            ->options(ThemeMode::class)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->columnSpan(4)
                            ->label(__('system.field.default_theme_mode')),

                        Select::make('appearance:color')
                            ->required()
                            ->options(array_combine(array_map('ucfirst', array_keys(Color::all())), array_map('ucfirst', array_keys(Color::all()))))
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->searchable()
                            ->columnSpan(4)
                            ->label(__('system.field.default_color')),

                        TextInput::make('contact:phone')
                            ->required()
                            ->prefix('+20')
                            ->rule('regex:/^(#|(\+20)?1[0125][0-9]{8})$/')
                            ->columnSpan(4)
                            ->label(__('system.field.phone')),

                        TextInput::make('contact:email')
                            ->required()
                            ->rule('regex:/^(#|.+@.+\..+)$/')
                            ->columnSpan(4)
                            ->label(__('system.field.email')),

                        TextInput::make('contact:address')
                            ->required()
                            ->columnSpan(4)
                            ->label(__('system.field.address')),


                        TextInput::make('social:facebook')
                            ->required()
                            ->rule(ValidOrHash::urlOrHash())
                            ->columnSpan(3)
                            ->label(__('system.field.facebook')),

                        TextInput::make('social:instagram')
                            ->required()
                            ->rule(ValidOrHash::urlOrHash())
                            ->columnSpan(3)
                            ->label(__('system.field.instagram')),

                        TextInput::make('social:twitter')
                            ->required()
                            ->rule(ValidOrHash::urlOrHash())
                            ->columnSpan(3)
                            ->label(__('system.field.twitter')),

                        TextInput::make('social:youtube')
                            ->required()
                            ->rule(ValidOrHash::urlOrHash())
                            ->columnSpan(3)
                            ->label(__('system.field.youtube')),
                    ])
                    ->columns(12)
                    ->columnSpanFull(),
            ]);
    }
}
