<?php

namespace App\Filament\Resources\Visions;

use App\Filament\Actions\ToggleBoolean;
use App\Filament\Forms\Components\IconSelect;
use App\Filament\Resources\Visions\Pages\ManageVisions;
use App\Filament\Tables\Columns\CustomIcon;
use App\Models\Vision;
use App\Services\VisionService;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class VisionResource extends Resource
{
    use Translatable, SoftDeletes;

    protected static ?string $model = Vision::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLightBulb;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getPluralModelLabel(): string
    {
        return __('system.vision.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.vision.model');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                IconSelect::make('icon')
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.icon')),

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

            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->recordTitleAttribute('title')
            ->afterReordering(fn(array $order) => app(VisionService::class)->flush())

            ->columns([
                CustomIcon::make('icon')
                    ->label(__('system.field.icon')),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(
                        fn(Vision $record): string => str($record->description)->limit(80)
                    )
                    ->label(__('system.field.title')),
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
                    ->service(VisionService::class)
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
            'index' => ManageVisions::route('/'),
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
