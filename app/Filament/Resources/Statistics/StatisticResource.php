<?php

namespace App\Filament\Resources\Statistics;

use App\Filament\Actions\ToggleBoolean;
use App\Filament\Forms\Components\IconSelect;
use App\Filament\Resources\Statistics\Pages\ManageStatistics;
use App\Filament\Tables\Columns\CustomIcon;
use App\Models\Statistic;
use App\Services\StatisticService;
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
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class StatisticResource extends Resource
{
    use SoftDeletes, Translatable;
    protected static ?string $model = Statistic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getPluralModelLabel(): string
    {
        return __('system.statistic.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.statistic.model');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                IconSelect::make('icon')
                    ->required()
                    ->columnSpan(4)
                    ->label(__('system.field.icon')),

                TextInput::make('title')
                    ->required()
                    ->columnSpan(4)
                    ->label(__('system.field.title')),

                TextInput::make('count')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->columnSpan(4)
                    ->label(__('system.field.count')),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->recordTitleAttribute('title')
            ->afterReordering(fn (array $order) => app(StatisticService::class)->flush())
            ->columns([
                CustomIcon::make('icon')
                    ->label(__('system.field.icon')),

                TextColumn::make('title')
                    ->searchable()
                    ->label(__('system.field.title')),

                TextColumn::make('count')
                    ->numeric()
                    ->sortable()
                    ->label(__('system.field.count')),

                IconColumn::make('is_visible')
                    ->boolean()
                    ->label(__('system.field.is_visible')),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                LocaleSwitcher::make(),
                CreateAction::make(),
            ])
            ->recordActions([
                ToggleBoolean::make()
                    ->field('is_visible')
                    ->service(StatisticService::class)
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
            'index' => ManageStatistics::route('/'),
        ];
    }
}
