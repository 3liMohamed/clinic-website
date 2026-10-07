<?php

namespace App\Filament\Resources\Pricings;

use App\Enums\PriceSuffix;
use App\Filament\Actions\ToggleBoolean;
use App\Filament\Forms\Components\IconSelect;
use App\Filament\Resources\Pricings\Pages\ManagePricings;
use App\Filament\Tables\Columns\CustomIcon;
use App\Models\Pricing;
use App\Rules\ValidOrHash;
use App\Services\PricingService;
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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
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

class PricingResource extends Resource
{
    use Translatable, SoftDeletes;

    protected static ?string $model = Pricing::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getPluralModelLabel(): string
    {
        return __('system.pricing.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.pricing.model');
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

                TextInput::make('price')
                    ->numeric()
                    ->prefix('EGP')
                    ->required()
                    ->minValue(0)
                    ->columnSpan(6)
                    ->label(__('system.field.price')),

                Select::make('price_suffix')
                    ->options(PriceSuffix::class)
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.price_suffix')),

                TextInput::make('button_text')
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.button_text')),

                TextInput::make('button_url')
                    ->rule(ValidOrHash::urlOrHash())
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.button_url')),

                Repeater::make('features')
                    ->relationship()
                    ->simple(
                        TextInput::make('text')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->label(__('system.field.text')),
                    )
                    ->schema([
                        TextInput::make('text')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(10)
                            ->label(__('system.field.text')),

                        Toggle::make('is_available')
                            ->default(false)
                            ->inline(false)
                            ->columnSpan(2)
                            ->label(__('system.field.is_available')),
                    ])
                    ->defaultItems(0)
                    ->columns(12)
                    ->columnSpanFull()
                    ->orderColumn('order')
                    ->reorderableWithButtons()
                    ->collapsed()
                    ->label(__('system.field.features')),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->recordTitleAttribute('title')
            ->afterReordering(fn(array $order) => app(PricingService::class)->flush())
            ->columns([
                CustomIcon::make('icon')
                    ->label(__('system.field.icon')),

                TextColumn::make('title')
                    ->searchable()
                    ->label(__('system.field.title')),

                TextColumn::make('price')
                    ->money('EGP')
                    ->sortable()
                    ->label(__('system.field.price')),

                TextColumn::make('price_suffix')
                    ->badge()
                    ->label(__('system.field.price_suffix')),

                TextColumn::make('features_count')
                    ->counts('features')
                    ->sortable()
                    ->label(__('system.field.features')),

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
                    ->service(PricingService::class)
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
            'index' => ManagePricings::route('/'),
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
