<?php

namespace App\Filament\Pages;

use App\Enums\WorkingDay;
use App\Models\Setting;
use App\Rules\ValidOrHash;
use App\Services\SettingService;
use BackedEnum;
use Filament\Enums\ThemeMode;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use LaraZeus\FilamentLocationPickrField\Forms\Components\LocationPickr;

class Settings extends Page
{
    use AuthorizesRequests, InteractsWithForms;

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

    public function canEdit(): bool
    {
        return Auth::user()?->can('updateAny', Setting::class) ?? false;
    }

    public ?array $data = [];

    protected array $originalData = [];

    public function mount(SettingService $settingService): void
    {
        $this->authorize('viewAny', Setting::class);

        $this->originalData = $settingService->getAll();

        $location = $this->originalData['location'] ?? null;

        if (is_string($location)) {
            $location = json_decode($location, true);
        }

        if (is_array($location)) {
            $this->originalData['location'] = $location;
        }

        $workingDays = $this->originalData['working_days'] ?? null;

        if (is_string($workingDays)) {
            $workingDays = json_decode($workingDays, true);
        }

        $this->originalData['working_days'] = $workingDays ?: collect(WorkingDay::cases())
            ->map(fn(WorkingDay $day) => [
                'day' => $day->value,
                'is_visible' => false,
                'start_time' => null,
                'end_time' => null,
                'total' => null,
            ])
            ->toArray();

        $this->form->fill($this->originalData);
    }

    public function save(SettingService $settingService): void
    {
        $this->authorize('updateAny', Setting::class);

        $data = $this->form->getState();


        foreach ($data as $key => $value) {
            if (($this->originalData[$key] ?? null) !== $value) {
                $settingService->set($key,
                    $key === 'location'
                        ? json_encode($value, JSON_THROW_ON_ERROR)
                        : $value,
                );

                $this->originalData[$key] = $value;
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
                    ->disabled(fn(): bool => ! $this->canEdit())
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
                            ->options(array_combine(
                                array_map('ucfirst', array_keys(Color::all())),
                                array_map('ucfirst', array_keys(Color::all()))
                            ))
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->searchable()
                            ->columnSpan(4)
                            ->label(__('system.field.default_color')),

                        TextInput::make('contact:phone')
                            ->required()
                            ->prefix('+20')
                            ->rule(ValidOrHash::phoneOrHash())
                            ->columnSpan(4)
                            ->label(__('system.field.phone')),

                        TextInput::make('contact:email')
                            ->required()
                            ->rule(ValidOrHash::emailOrHash())
                            ->columnSpan(4)
                            ->label(__('system.field.email')),

                        TextInput::make('contact:address')
                            ->default('#')
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

                        TextInput::make('social:x')
                            ->required()
                            ->rule(ValidOrHash::urlOrHash())
                            ->columnSpan(3)
                            ->label(__('system.field.x')),

                        TextInput::make('social:youtube')
                            ->required()
                            ->rule(ValidOrHash::urlOrHash())
                            ->columnSpan(3)
                            ->label(__('system.field.youtube')),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columns(12)
                    ->columnSpanFull(),
                $this->clinicLocation(),
                $this->sectionWorkingDays(),
            ]);
    }

    private function sectionWorkingDays(): Section
    {
        return Section::make('working-days')
            ->disabled(fn(): bool => ! $this->canEdit())
            ->heading(__('system.settings.working_days.title'))
            ->description(__('system.settings.working_days.description'))
            ->schema([
                Repeater::make('working_days')
                    ->table([
                        TableColumn::make(__('system.field.day'))
                            ->width('125px'),

                        TableColumn::make(__('system.field.is_visible'))
                            ->width('0px'),

                        TableColumn::make(__('system.field.start_time'))
                            ->width('150px'),

                        TableColumn::make(__('system.field.end_time'))
                            ->width('150px'),

                        TableColumn::make(__('system.field.total'))
                            ->width('150px'),
                    ])
                    ->schema([
                        Hidden::make('day'),

                        TextEntry::make('day_view')
                            ->statePath('day')
                            ->formatStateUsing(fn($state) => WorkingDay::from($state)->getLabel())
                            ->color(fn($state) => WorkingDay::from($state)->getColor())
                            ->size(TextSize::Large)
                            ->alignment(Alignment::Center)
                            ->badge()
                            ->icon('heroicon-o-clock')
                            ->columnSpan(2),

                        Toggle::make('is_visible')
                            ->inline(false)
                            ->live()
                            ->onIcon(Heroicon::Eye)
                            ->offIcon(Heroicon::EyeSlash)
                            ->columnSpan(2)
                            ->label(__('system.field.is_visible')),

                        TimePicker::make('start_time')
                            ->seconds(false)
                            ->displayFormat('H:i')
                            ->live()
                            ->required(fn(Get $get): bool => (bool) $get('is_visible'))
                            ->disabled(fn(Get $get): bool => ! $get('is_visible'))
                            ->dehydrated()
                            ->afterStateUpdated(fn(Set $set, Get $get) => $set('total', $this->calculateDuration($get('start_time'), $get('end_time'))))
                            ->columnSpan(3)
                            ->label(__('system.field.start_time')),

                        TimePicker::make('end_time')
                            ->seconds(false)
                            ->displayFormat('H:i')
                            ->live()
                            ->required(fn(Get $get): bool => (bool) $get('is_visible'))
                            ->disabled(fn(Get $get): bool => ! $get('is_visible'))
                            ->dehydrated()
                            ->afterStateUpdated(fn(Set $set, Get $get) => $set('total', $this->calculateDuration($get('start_time'), $get('end_time'))))
                            ->columnSpan(3)
                            ->label(__('system.field.end_time')),

                        TextInput::make('total')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(3)
                            ->label(__('system.field.total')),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->hiddenLabel()
                    ->columns(12)
                    ->columnSpanFull(),
            ])
            ->collapsible()
            ->collapsed()
            ->columns(12)
            ->columnSpanFull();
    }

    private function calculateDuration(?string $start, ?string $end): ?string
    {
        if (! $start || ! $end) {
            return null;
        }

        $startTime = strtotime($start);
        $endTime = strtotime($end);

        $diff = $endTime - $startTime;

        if ($diff < 0) {
            $diff += 86400;
        }

        $hours = floor($diff / 3600);
        $minutes = floor(($diff % 3600) / 60);

        return "{$hours}h {$minutes}m";
    }

    private function clinicLocation(): Section
    {
        return Section::make('location')
            ->heading(__('system.settings.location.title'))
            ->description(__('system.settings.location.description'))
            ->disabled(fn(): bool => ! $this->canEdit())
            ->schema([
                LocationPickr::make('location')
                    ->defaultLocation([30.0131, 31.2089])
                    ->defaultZoom(13)
                    ->draggable()
                    ->clickable()
                    ->height('400px')
                    ->columnSpanFull()
                    ->label(__('system.field.location')),
            ])
            ->collapsible()
            ->collapsed()
            ->columns(12)
            ->columnSpanFull();
    }
}
