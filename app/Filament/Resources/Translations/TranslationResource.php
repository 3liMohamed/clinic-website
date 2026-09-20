<?php

namespace App\Filament\Resources\Translations;

use App\Filament\Resources\Translations\Pages\ManageTranslations;
use App\Models\Translation;
use BackedEnum;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class TranslationResource extends Resource
{
    use Translatable;

    protected static ?string $model = Translation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    public static function getPluralModelLabel(): string
    {
        return __('system.translation.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.translation.model');
    }

    public static function getNavigationGroup(): string
    {
        return __('system.settings.group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->required()
                    ->maxLength(255)
                    ->default('site')
                    ->disabled(app()->isProduction())
                    ->dehydrated()
                    ->columnSpan(6)
                    ->label(__('system.field.group')),
                TextInput::make('key')
                    ->required()
                    ->maxLength(255)
                    ->disabled(app()->isProduction())
                    ->dehydrated()
                    ->columnSpan(6)
                    ->label(__('system.field.key')),
                Textarea::make('value')
                    ->required()
                    ->columnSpanFull()
                    ->label(__('system.field.value')),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('key')
            ->defaultGroup('group')
            ->columns([
                TextColumn::make('group')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.field.group')),

                TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.field.key')),

                TextColumn::make('value')
                    ->limit(80)
                    ->label(__('system.field.value')),
            ])
            ->headerActions([
                LocaleSwitcher::make(),
                CreateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTranslations::route('/'),
        ];
    }
}
