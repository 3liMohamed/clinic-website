<?php

namespace App\Filament\Resources\Slides;

use App\Filament\Resources\Slides\Pages\ManageSlides;
use App\Models\Slide;
use App\Rules\ValidOrHash;
use App\Services\SlideService;
use BackedEnum;
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

class SlideResource extends Resource
{
    use SoftDeletes, Translatable;

    protected static ?string $model = Slide::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getPluralModelLabel(): string
    {
        return __('system.slide.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.slide.model');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->image()
                    ->imageEditor()
                    ->disk('images')
                    ->directory('slides')
                    ->columnSpanFull()
                    ->label(__('system.field.image')),

                TextInput::make('title')
                    ->required()
                    ->columnSpan(10)
                    ->label(__('system.field.title')),

                Toggle::make('is_visible')
                    ->default(true)
                    ->inline(false)
                    ->onColor('success')
                    ->offColor('danger')
                    ->columnSpan(2)
                    ->label(__('system.field.is_visible')),

                Textarea::make('description')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull()
                    ->label(__('system.field.description')),

                TextInput::make('primary_button_text')
                    ->required()
                    ->columnSpan(3)
                    ->label(__('system.field.primary_button_text')),

                TextInput::make('primary_button_url')
                    ->required()
                    ->rule(ValidOrHash::urlOrHash())
                    ->columnSpan(3)
                    ->label(__('system.field.primary_button_url')),

                TextInput::make('secondary_button_text')
                    ->required()
                    ->columnSpan(3)
                    ->label(__('system.field.secondary_button_text')),

                TextInput::make('secondary_button_url')
                    ->required()
                    ->rule(ValidOrHash::urlOrHash())
                    ->columnSpan(3)
                    ->label(__('system.field.secondary_button_url')),


            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->recordTitleAttribute('title')
            ->afterReordering(fn (array $order) => app(SlideService::class)->flush())
            ->columns([
                ImageColumn::make('image')
                    ->disk('images')
                    ->imageSize(100)
                    ->label(__('system.field.image')),

                TextColumn::make('title')
                    ->description(fn(Slide $record): ?string => Str::limit($record->description, 50))
                    ->searchable()
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
            'index' => ManageSlides::route('/'),
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
