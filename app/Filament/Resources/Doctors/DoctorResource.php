<?php

namespace App\Filament\Resources\Doctors;

use App\Enums\WorkingDay;
use App\Filament\Actions\ToggleBoolean;
use App\Filament\Actions\ToggleHome;
use App\Filament\Forms\Components\IconSelect;
use App\Filament\Resources\Doctors\Pages\ManageDoctors;
use App\Models\Doctor;
use App\Rules\ValidOrHash;
use App\Services\DoctorService;
use App\Services\SettingService;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class DoctorResource extends Resource
{
    use Translatable, SoftDeletes;

    protected static ?string $model = Doctor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getPluralModelLabel(): string
    {
        return __('system.doctor.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('system.doctor.model');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make(__('system.field.info'))
                        ->icon(Heroicon::OutlinedInformationCircle)
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->imageEditor()
                                ->default('default.webp')
                                ->disk('images')
                                ->directory('doctors')
                                ->maxSize(2048)
                                ->columnSpanFull()
                                ->label(__('system.field.image')),


                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(4)
                                ->label(__('system.field.name')),

                            Select::make('department_id')
                                ->relationship('department', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpan(4)
                                ->label(__('system.field.department')),

                            Toggle::make('is_visible')
                                ->live()
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->columnSpan(2)
                                ->label(__('system.field.is_visible'))
                                ->afterStateUpdated(function (Set $set, bool $state): void {
                                    if (! $state) {
                                        $set('has_detail_page', false);
                                    }
                                }),

                            Toggle::make('has_detail_page')
                                ->live()
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->columnSpan(2)
                                ->disabled(fn(Get $get): bool => ! $get('is_visible'))
                                ->dehydrateStateUsing(fn(Get $get, bool $state): bool => $get('is_visible') ? $state : false)
                                ->label(__('system.field.has_detail_page')),

                            RichEditor::make('biography')
                                ->required()
                                ->visible(fn(Get $get): bool => $get('has_detail_page'))
                                ->toolbarButtons([
                                    'bold',
                                    'italic',
                                    'underline',
                                    'strike',
                                    'textColor',
                                    'link',
                                    'bulletList',
                                    'orderedList',
                                    'h2',
                                    'h3',
                                    'blockquote',
                                    'undo',
                                    'redo',
                                ])
                                ->columnSpanFull()
                                ->label(__('system.field.biography')),
                        ])->columns(12),

                    Step::make(__('system.field.conact_info'))
                        ->icon(Heroicon::OutlinedPhoto)
                        ->visible(fn(Get $get): bool => $get('has_detail_page'))
                        ->schema([
                            TextInput::make('phone')
                                ->prefix('+20')
                                ->default('#')
                                ->rule(ValidOrHash::phoneOrHash())
                                ->required()
                                ->columnSpan(4)
                                ->label(__('system.field.phone')),

                            TextInput::make('email')
                                ->default('#')
                                ->rule(ValidOrHash::emailOrHash())
                                ->required()
                                ->columnSpan(4)
                                ->label(__('system.field.email')),

                            TextInput::make('address')
                                ->default('#')
                                ->required()
                                ->columnSpan(4)
                                ->label(__('system.field.address')),

                            Repeater::make('socials')
                                ->schema([
                                    IconSelect::make('icon')
                                        ->required()
                                        ->columnSpan(3)
                                        ->label(__('system.field.icon')),

                                    TextInput::make('url')
                                        ->required()
                                        ->url()
                                        ->columnSpan(7)
                                        ->label(__('system.field.url')),

                                    Toggle::make('is_visible')
                                        ->default(true)
                                        ->onColor('success')
                                        ->offColor('danger')
                                        ->inline(false)
                                        ->columnSpan(2)
                                        ->label(__('system.field.is_visible')),
                                ])
                                ->columns(12)
                                ->defaultItems(0)
                                ->collapsible()
                                ->itemLabel(fn(array $state): ?string => $state['url'] ?? null)
                                ->columnSpanFull()
                                ->label(__('system.field.socials')),

                        ])->columns(12),


                    Step::make(__('system.field.meta'))
                        ->icon(Heroicon::GlobeAlt)
                        ->visible(fn(Get $get): bool => $get('has_detail_page'))
                        ->schema([
                            TagsInput::make('meta_keywords')
                                ->required()
                                ->separator(',')
                                ->columnSpanFull()
                                ->label(__('system.field.meta_keywords')),

                            Textarea::make('meta_description')
                                ->required()
                                ->rows(3)
                                ->maxLength(255)
                                ->minLength(3)
                                ->columnSpanFull()
                                ->label(__('system.field.meta_description')),
                        ]),


                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->reorderable('order')
            ->recordTitleAttribute('name')
            ->afterReordering(fn(array $order) => app(DoctorService::class)->flush())
            ->columns([
                ImageColumn::make('image')
                    ->disk('images')
                    ->imageSize(100)
                    ->label(__('system.field.image')),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.field.name')),

                TextColumn::make('department.name')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.field.department')),

                IconColumn::make('is_visible')
                    ->boolean()
                    ->label(__('system.field.is_visible')),
            ])
            ->headerActions([
                LocaleSwitcher::make(),
                CreateAction::make(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('workingHours')
                    ->visible(
                        fn(Doctor $record): bool =>
                        $record->is_visible && $record->has_detail_page
                    )
                    ->authorize(
                        fn(Doctor $record): bool =>
                        auth()->user()->can('workingHours', $record)
                    )
                    ->icon('heroicon-o-clock')
                    ->iconButton()
                    ->color('gray')
                    ->fillForm(
                        fn(Doctor $record): array => [
                            'working_hours' => $record->workingHours()
                                ->orderBy('day')
                                ->get()
                                ->map(fn($hour) => [
                                    'id' => $hour->id,
                                    'day' => $hour->day->value,
                                    'start_time' => $hour->start_time,
                                    'end_time' => $hour->end_time,
                                    'is_visible' => $hour->is_visible,
                                ])
                                ->toArray(),
                        ]
                    )
                    ->schema([
                        Repeater::make('working_hours')
                            ->table([
                                TableColumn::make(__('system.field.day'))
                                    ->width('125px'),

                                TableColumn::make(__('system.field.is_visible'))
                                    ->width('0px'),

                                TableColumn::make(__('system.field.start_time'))
                                    ->width('150px'),

                                TableColumn::make(__('system.field.end_time'))
                                    ->width('150px'),
                            ])
                            ->schema([
                                Hidden::make('id'),

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
                                    ->disabled(function (Get $get): bool {
                                        $workingDays = app(SettingService::class)->get('working_days');
                                        $workingDays = json_decode($workingDays, true);

                                        $settingsDay = collect($workingDays)->firstWhere('day', $get('day'));

                                        return ! ($settingsDay['is_visible'] ?? false);
                                    })
                                    ->dehydrated()
                                    ->onIcon(Heroicon::Eye)
                                    ->offIcon(Heroicon::EyeSlash)
                                    ->columnSpan(2)
                                    ->label(__('system.field.is_visible')),

                                TimePicker::make('start_time')
                                    ->seconds(false)
                                    ->displayFormat('g:i A')
                                    ->format('H:i')
                                    ->live()
                                    ->required(fn(Get $get): bool => $get->boolean('is_visible'))
                                    ->disabled(fn(Get $get): bool => ! $get->boolean('is_visible'))
                                    ->dehydrated()
                                    ->rule(function (Get $get) {
                                        return function (string $attribute, $value, \Closure $fail) use ($get) {

                                            if (! $value || $get('day') === null) {
                                                return;
                                            }

                                            $workingDays = app(SettingService::class)->get('working_days');

                                            $workingDays = json_decode($workingDays, true);

                                            $settingsDay = collect($workingDays)
                                                ->firstWhere('day', $get('day'));

                                            if (! $settingsDay || ! $settingsDay['is_visible']) {
                                                return;
                                            }

                                            $startTime = Carbon::parse($value);

                                            $settingsStartTime = Carbon::parse($settingsDay['start_time']);

                                            if ($startTime->lt($settingsStartTime)) {
                                                $fail(__('validation.start_time_outside_working_hours'));
                                            }
                                        };
                                    })
                                    ->columnSpan(3)
                                    ->label(__('system.field.start_time')),

                                TimePicker::make('end_time')
                                    ->seconds(false)
                                    ->displayFormat('g:i A')
                                    ->format('H:i')
                                    ->live()
                                    ->required(fn(Get $get): bool => $get->boolean('is_visible'))
                                    ->disabled(fn(Get $get): bool => ! $get->boolean('is_visible'))
                                    ->dehydrated()
                                    ->rule(function (Get $get) {
                                        return function (string $attribute, $value, \Closure $fail) use ($get) {

                                            if (! $value || $get('day') === null) {
                                                return;
                                            }

                                            $workingDays = app(SettingService::class)->get('working_days');

                                            $workingDays = json_decode($workingDays, true);

                                            $settingsDay = collect($workingDays)
                                                ->firstWhere('day', $get('day'));

                                            if (! $settingsDay || ! $settingsDay['is_visible']) {
                                                return;
                                            }

                                            $startTime = Carbon::parse($get('start_time'));
                                            $endTime = Carbon::parse($value);

                                            $settingsStartTime = Carbon::parse($settingsDay['start_time']);
                                            $settingsEndTime = Carbon::parse($settingsDay['end_time']);

                                            if ($settingsEndTime->lessThanOrEqualTo($settingsStartTime)) {
                                                $settingsEndTime->addDay();
                                            }

                                            if ($endTime->lessThan($startTime)) {
                                                $endTime->addDay();
                                            }

                                            if ($endTime->gt($settingsEndTime)) {
                                                $fail(__('validation.end_time_outside_working_hours'));
                                            }
                                        };
                                    })
                                    ->columnSpan(3)
                                    ->label(__('system.field.end_time')),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->hiddenLabel()
                            ->columns(12)
                            ->columnSpanFull(),
                    ])
                    ->action(function (Doctor $record, array $data): void {
                        foreach ($data['working_hours'] as $row) {
                            $record->workingHours()->updateOrCreate(
                                [
                                    'id' => $row['id'] ?? null,
                                ],
                                [
                                    'day' => $row['day'],
                                    'start_time' => $row['start_time'],
                                    'end_time' => $row['end_time'],
                                    'is_visible' => $row['is_visible'],
                                ]
                            );
                        }

                        app(DoctorService::class)->flush();

                        Notification::make()
                            ->title(__('system.notification.updated'))
                            ->success()
                            ->send();
                    }),

                ToggleBoolean::make()
                    ->field('is_home')
                    ->service(DoctorService::class)
                    ->visibleWhen(fn(Doctor $record): bool => $record->is_visible)
                    ->method('toggleHome')
                    ->maxMethod('maxHome')
                    ->policyAbility('toggleHome'),

                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDoctors::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
