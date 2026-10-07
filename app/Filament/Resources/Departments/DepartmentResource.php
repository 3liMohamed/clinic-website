<?php

namespace App\Filament\Resources\Departments;

use App\Filament\Actions\ToggleBoolean;
use App\Filament\Forms\Components\IconSelect;
use App\Filament\Resources\Departments\Pages\ManageDepartments;
use App\Filament\Tables\Columns\CustomIcon;
use App\Models\Department;
use App\Services\DepartmentService;
use BackedEnum;
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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use Illuminate\Support\Str;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class DepartmentResource extends Resource
{
    use Translatable, SoftDeletes;

    protected static ?string $model = Department::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getPluralModelLabel(): string
    {
        return __('system.department.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.department.model');
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
                    ->directory('departments')
                    ->maxSize(2048)
                    ->columnSpanFull()
                    ->label(__('system.field.image')),

                TextInput::make('name')
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.name')),

                IconSelect::make('icon')
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.icon')),

                Textarea::make('description')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull()
                    ->label(__('system.field.description')),

                Repeater::make('items')
                    ->relationship()
                    ->simple(
                        TextInput::make('text')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->label(__('system.field.text')),
                    )
                    ->defaultItems(0)
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
            ->reorderable('order')
            ->defaultSort('order')
            ->recordTitleAttribute('name')
            ->afterReordering(fn(array $order) => app(DepartmentService::class)->flush())
            ->columns([
                ImageColumn::make('image')
                    ->disk('images')
                    ->imageSize(100)
                    ->square()
                    ->label(__('system.field.image')),

                CustomIcon::make('icon')
                    ->label(__('system.field.icon')),

                TextColumn::make('name')
                    ->description(fn(Department $record): ?string => Str::limit($record->description, 50))
                    ->searchable()
                    ->label(__('system.field.name')),

                TextColumn::make('items_count')
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
                ToggleBoolean::make()
                    ->field('is_visible')
                    ->service(DepartmentService::class)
                    ->method('toggleVisibility')
                    ->maxMethod('maxVisible')
                    ->policyAbility('toggleVisibility'),

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
            'index' => ManageDepartments::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount([
                'items' => fn($query) => $query->withTrashed(),
            ]);
    }
}
