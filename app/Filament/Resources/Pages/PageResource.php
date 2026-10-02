<?php

namespace App\Filament\Resources\Pages;

use App\Enums\PageType;
use App\Filament\Resources\Pages\Pages\ManagePages;
use App\Models\Page;
use App\Rules\ValidOrHash;
use App\Services\PageService;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
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
                                ->visible(fn(Get $get): bool => (int) $get('type')->value === PageType::Main->value)
                                ->label(__('system.field.image')),

                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->disabled(app()->isProduction())
                                ->dehydrated()
                                ->columnSpan(Auth::id() === 1 ? 4 : 5)
                                ->label(__('system.field.name')),

                            Select::make('type')
                                ->options(PageType::class)
                                ->required()
                                ->native(false)
                                ->live()
                                ->disabled(app()->isProduction())
                                ->dehydrated()
                                ->columnSpan(Auth::id() === 1 ? 4 : 5)
                                ->label(__('system.field.type')),

                            Toggle::make('is_visible')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->visible(Auth::id() === 1)
                                ->columnSpan(Auth::id() === 1 ? 2 : 0)
                                ->label(__('system.field.is_visible')),

                            Toggle::make('show_breadcrumb')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->live()
                                ->columnSpan(2)
                                ->label(__('system.field.show_breadcrumb')),

                            TextInput::make('header_title')
                                ->required(fn(Get $get): bool => (int) $get('type')->value === PageType::Main->value)
                                ->maxLength(255)
                                ->columnSpan(6)
                                ->visible(fn(Get $get): bool => (int) $get('type')->value === PageType::Main->value)
                                ->label(__('system.field.header_title')),

                            TextInput::make('nav_title')
                                ->required(fn(Get $get): bool => (int) $get('type')->value === PageType::Main->value)
                                ->maxLength(255)
                                ->columnSpan(6)
                                ->visible(fn(Get $get): bool => (int) $get('type')->value === PageType::Main->value)
                                ->label(__('system.field.nav_title')),

                            Toggle::make('show_in_main_nav')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->columnSpan(4)
                                ->visible(fn(Get $get): bool => (int) $get('type')->value === PageType::Main->value)
                                ->label(__('system.field.show_in_main_nav')),

                            Toggle::make('show_in_top_nav')
                                ->default(false)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->columnSpan(4)
                                ->visible(fn(Get $get): bool => (int) $get('type')->value === PageType::Main->value)
                                ->label(__('system.field.show_in_top_nav')),

                            Toggle::make('is_cta')
                                ->default(false)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->columnSpan(4)
                                ->visible(fn(Get $get): bool => (int) $get('type')->value === PageType::Main->value)
                                ->label(__('system.field.is_cta')),

                        ])->columns(12),

                    Step::make(__('system.field.sections_meta'))
                        ->icon(Heroicon::GlobeAlt)
                        ->visible(fn(Get $get): bool => (int) $get('type')->value === PageType::Main->value)
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

                    Step::make(__('system.field.breadcrumb_info'))
                        ->icon(Heroicon::RectangleStack)
                        ->visible(fn(Get $get): bool => (bool) $get('show_breadcrumb'))
                        ->schema([
                            FileUpload::make('breadcrumb_image')
                                ->image()
                                ->imageEditor()
                                ->disk('images')
                                ->directory('breadcrumbs')
                                ->columnSpanFull()
                                ->label(__('system.field.breadcrumb_image')),

                            TextInput::make('breadcrumb_title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull()
                                ->label(__('system.field.breadcrumb_title')),

                            Repeater::make('breadcrumbItems')
                                ->relationship()
                                ->schema([
                                    TextInput::make('text')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(6)
                                        ->label(__('system.field.name')),

                                    TextInput::make('url')
                                        ->required()
                                        ->rule(ValidOrHash::urlOrHash())
                                        ->columnSpan(6)
                                        ->label(__('system.field.url')),

                                ])
                                ->label(__('system.field.breadcrumb_sections'))
                                ->defaultItems(0)
                                ->maxItems(3)
                                ->columns(12)
                                ->columnSpanFull()
                                ->orderColumn('order')
                                ->reorderableWithButtons()
                                ->collapsed(),
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
                                ->defaultItems(0)
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
            ->reorderable('order')
            ->defaultSort('order')
            ->recordTitleAttribute('name')
            ->afterReordering(fn(array $order) => app(PageService::class)->flush())
            ->columns([
                ImageColumn::make('image')
                    ->disk('images')
                    ->imageSize(100)
                    ->label(__('system.field.image')),

                TextColumn::make('name')
                    ->searchable()
                    ->label(__('system.field.name')),

                TextColumn::make('header_title')
                    ->searchable()
                    ->label(__('system.field.header_title')),

                TextColumn::make('nav_title')
                    ->searchable()
                    ->label(__('system.field.nav_title')),

                TextColumn::make('type')
                    ->badge()
                    ->label(__('system.field.type')),

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
            ->filters([
                SelectFilter::make('type')
                    ->options(PageType::class)
                    ->label(__('system.field.type')),

                TernaryFilter::make('is_visible')
                    ->label(__('system.field.is_visible')),

                TernaryFilter::make('show_breadcrumb')
                    ->label(__('system.field.show_breadcrumb')),
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
