<?php

namespace App\Filament\Resources\WebsiteGalleries;

use App\Filament\Resources\WebsiteGalleries\Pages\ManageWebsiteGalleries;
use App\Models\WebsiteGallery;
use BackedEnum;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class WebsiteGalleryResource extends Resource
{
    use Translatable;

    protected static ?string $model = WebsiteGallery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getPluralModelLabel(): string
    {
        return __('system.website_gallery.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.website_gallery.model');
    }

    public static function getNavigationGroup(): string
    {
        return __('system.settings.group');
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
                    ->directory('galleries')
                    ->maxSize(2048)
                    ->columnSpanFull()
                    ->label(__('system.field.image')),

                TextInput::make('key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled(app()->isProduction())
                    ->suffixIcon(Heroicon::Key)
                    ->suffixIconColor('primary')
                    ->maxLength(255)
                    ->label(__('system.field.key')),

                TextInput::make('title')
                    ->required()
                    ->suffixIcon(Heroicon::Clipboard)
                    ->suffixIconColor('primary')
                    ->maxLength(255)
                    ->label(__('system.field.title')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('image')
                    ->disk('images')
                    ->imageHeight(100)
                    ->imageWidth(100)
                    ->square()
                    ->label(__('system.field.image')),

                TextColumn::make('key')
                    ->icon(Heroicon::Key)
                    ->iconColor('primary')
                    ->label(__('system.field.key')),

                TextColumn::make('title')
                    ->searchable()
                    ->icon(Heroicon::Clipboard)
                    ->iconColor('primary')
                    ->label(__('system.field.title')),
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
            'index' => ManageWebsiteGalleries::route('/'),
        ];
    }
}
