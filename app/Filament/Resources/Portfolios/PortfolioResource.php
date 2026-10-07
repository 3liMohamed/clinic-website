<?php

namespace App\Filament\Resources\Portfolios;

use App\Filament\Resources\Portfolios\Pages\ManagePortfolios;
use App\Models\Portfolio;
use App\Services\PortfolioService;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class PortfolioResource extends Resource
{
    use SoftDeletes, Translatable;

    protected static ?string $model = Portfolio::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getPluralModelLabel(): string
    {
        return __('system.portfolio.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.portfolio.model');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make(__('system.field.info'))
                        ->icon(Heroicon::OutlinedInformationCircle)
                        ->schema([
                            FileUpload::make('cover_image')
                                ->image()
                                ->imageEditor()
                                ->default('default.webp')
                                ->disk('images')
                                ->directory('portfolios')
                                ->maxSize(2048)
                                ->visible(fn(Get $get): bool => ! $get('has_detail_page'))
                                ->columnSpanFull()
                                ->label(__('system.field.cover_image')),

                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(8)
                                ->label(__('system.field.title')),

                            Toggle::make('is_visible')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->columnSpan(2)
                                ->label(__('system.field.is_visible')),

                            Toggle::make('has_detail_page')
                                ->live()
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->columnSpan(2)
                                ->label(__('system.field.has_detail_page')),

                            Select::make('category_id')
                                ->visible(fn(Get $get): bool => $get('has_detail_page'))
                                ->relationship('category', 'name', fn(Builder $query) => $query->visible())
                                ->searchable()
                                ->preload()
                                ->columnSpan(4)
                                ->label(__('system.field.category')),

                            TextInput::make('client')
                                ->visible(fn(Get $get): bool => $get('has_detail_page'))
                                ->required(fn(Get $get): bool => $get('has_detail_page'))
                                ->maxLength(255)
                                ->columnSpan(4)
                                ->label(__('system.field.client')),

                            DatePicker::make('project_date')
                                ->visible(fn(Get $get): bool => $get('has_detail_page'))
                                ->required(fn(Get $get): bool => $get('has_detail_page'))
                                ->native(false)
                                ->format('Y-m-d')
                                ->columnSpan(4)
                                ->label(__('system.field.project_date')),

                            RichEditor::make('content')
                                ->required(fn(Get $get): bool => $get('has_detail_page'))
                                ->visible(fn(Get $get): bool => $get('has_detail_page'))
                                ->toolbarButtons([
                                    'bold',
                                    'italic',
                                    'underline',
                                    'strike',
                                    'textColor',
                                    'link',
                                    'bulletList',
                                    'orderedList',
                                    'h2',
                                    'h3',
                                    'attachFiles',
                                    'blockquote',
                                    'undo',
                                    'redo',
                                ])
                                ->fileAttachmentsDisk('images')
                                ->fileAttachmentsDirectory('portfolios/content')
                                ->columnSpanFull()
                                ->label(__('system.field.content')),
                        ])->columns(12),

                    Step::make(__('system.field.gallery'))
                        ->icon(Heroicon::OutlinedPhoto)
                        ->visible(fn(Get $get): bool => $get('has_detail_page'))
                        ->schema([
                            FileUpload::make('cover_image')
                                ->image()
                                ->imageEditor()
                                ->default('default.webp')
                                ->disk('images')
                                ->directory('portfolios')
                                ->maxSize(2048)
                                ->columnSpanFull()
                                ->label(__('system.field.cover_image')),

                            FileUpload::make('gallery')
                                ->required()
                                ->image()
                                ->multiple()
                                ->imageEditor()
                                ->disk('images')
                                ->directory('portfolios')
                                ->maxSize(2048)
                                ->columnSpanFull()
                                ->label(__('system.field.gallery')),
                        ])->columns(12),


                    Step::make(__('system.field.meta'))
                        ->icon(Heroicon::GlobeAlt)
                        ->visible(fn(Get $get): bool => $get('has_detail_page'))
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


                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->recordTitleAttribute('title')
            ->afterReordering(fn(array $order) => app(PortfolioService::class)->flush())
            ->columns([
                ImageColumn::make('cover_image')
                    ->disk('images')
                    ->imageSize(100)
                    ->label(__('system.field.cover_image')),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.field.title')),

                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.field.category')),

                TextColumn::make('client')
                    ->searchable()
                    ->placeholder('-')
                    ->label(__('system.field.client')),

                TextColumn::make('project_date')
                    ->date()
                    ->sortable()
                    ->placeholder('-')
                    ->label(__('system.field.project_date')),

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
                TrashedFilter::make(),

                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label(__('system.field.category')),
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
            'index' => ManagePortfolios::route('/'),
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
