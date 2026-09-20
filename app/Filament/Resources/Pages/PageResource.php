<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\ManagePages;
use App\Models\Page;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class PageResource extends Resource
{
    use Translatable;

    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getPluralModelLabel(): string
    {
        return __('system.page.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.page.model');
    }

    public static function getNavigationGroup(): string
    {
        return __('system.settings.group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make(__('system.field.page_info'))
                        ->icon(Heroicon::DocumentText)
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->imageEditor()
                                ->default('og/default.webp')
                                ->disk('images')
                                ->directory('og')
                                ->maxSize(2048)
                                ->columnSpanFull()
                                ->label(__('system.field.image')),
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->disabled(app()->isProduction())
                                ->dehydrated()
                                ->columnSpanFull()
                                ->label(__('system.field.name')),
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(10)
                                ->label(__('system.field.title')),
                            Toggle::make('is_visible')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->visible(Auth::id() === 1)
                                ->columnSpan(Auth::id() === 1 ? 2 : 0)
                                ->label(__('system.field.is_visible')),
                        ])->columns(12),
                    Step::make(__('system.field.sections_meta'))
                        ->icon(Heroicon::GlobeAlt)
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
                    Step::make(__('system.field.sections_info'))
                        ->icon(Heroicon::RectangleStack)
                        ->schema([
                            Repeater::make('sections')
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255)
                                        ->disabled(app()->isProduction())
                                        ->dehydrated()
                                        ->columnSpan(10)
                                        ->label(__('system.field.name')),

                                    Toggle::make('is_visible')
                                        ->default(true)
                                        ->onColor('success')
                                        ->offColor('danger')
                                        ->inline(false)
                                        ->columnSpan(2)
                                        ->label(__('system.field.is_visible')),

                                ])
                                ->label(__('system.field.page_sections'))
                                ->defaultItems(1)
                                ->columns(12)
                                ->columnSpanFull()
                                ->orderColumn('sort')
                                ->reorderableWithButtons()
                                ->collapsed()
                                ->addable(app()->isLocal())
                                ->deletable(app()->isLocal()),
                        ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('image')
                    ->disk('images')
                    ->imageHeight(100)
                    ->imageWidth(100)
                    ->label(__('system.field.image')),
                TextColumn::make('name')
                    ->searchable()
                    ->label(__('system.field.name')),
                TextColumn::make('title')
                    ->searchable()
                    ->label(__('system.field.title')),
                TextColumn::make('meta_keywords')
                    ->searchable()
                    ->badge()
                    ->label(__('system.field.meta_keywords')),
                IconColumn::make('is_visible')
                    ->boolean()
                    ->label(__('system.field.is_visible')),
            ])
            ->headerActions([
                LocaleSwitcher::make(),
                CreateAction::make(),
            ])
            ->recordActions([
                ViewAction::make()->iconButton(),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePages::route('/'),
        ];
    }
}
