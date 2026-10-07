<?php

namespace App\Filament\Resources\Missions;

use App\Filament\Actions\MakeVisible;
use App\Filament\Forms\Components\IconSelect;
use App\Filament\Resources\Missions\Pages\ManageMissions;
use App\Models\Mission;
use App\Services\MissionService;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Illuminate\Support\Str;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;

class MissionResource extends Resource
{
    use Translatable, SoftDeletes;

    protected static ?string $model = Mission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

    protected static ?string $recordTitleAttribute = 'sub_title';

    public static function getPluralModelLabel(): string
    {
        return __('system.mission.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.mission.model');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->required()
                    ->image()
                    ->imageEditor()
                    ->disk('images')
                    ->directory('missions')
                    ->maxSize(2048)
                    ->columnSpanFull()
                    ->label(__('system.field.image')),

                TextInput::make('sub_title')
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.sub_title')),

                TextInput::make('title')
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.title')),

                Textarea::make('description')
                    ->required()
                    ->rows(3)
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->label(__('system.field.description')),

                Repeater::make('items')
                    ->relationship()
                    ->schema([
                        IconSelect::make('icon')
                            ->required()
                            ->columnSpan(5)
                            ->label(__('system.field.icon')),

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(5)
                            ->label(__('system.field.text')),

                        Toggle::make('is_visible')
                            ->default(false)
                            ->inline(false)
                            ->columnSpan(2)
                            ->label(__('system.field.is_visible')),

                        Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->label(__('system.field.description')),
                    ])
                    ->defaultItems(0)
                    ->maxItems(6)
                    ->columns(12)
                    ->columnSpanFull()
                    ->orderColumn('order')
                    ->reorderableWithButtons()
                    ->collapsed()
                    ->label(__('system.field.items')),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sub_title')
            ->columns([
                ImageColumn::make('image')
                    ->disk('images')
                    ->imageSize(100)
                    ->label(__('system.field.image')),

                TextColumn::make('sub_title')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.field.sub_title')),

                TextColumn::make('title')
                    ->description(fn(Mission $record): ?string => Str::limit($record->description, 50))
                    ->searchable()
                    ->label(__('system.field.title')),

                TextColumn::make('items_count')
                    ->counts('items')
                    ->sortable()
                    ->label(__('system.field.items')),

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
                MakeVisible::make()
                    ->service(MissionService::class)
                    ->policyAbility('visibility'),
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
            'index' => ManageMissions::route('/'),
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
