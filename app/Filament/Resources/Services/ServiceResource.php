<?php

namespace App\Filament\Resources\Services;

use App\Filament\Forms\Components\IconSelect;
use App\Filament\Resources\Services\Pages\ManageServices;
use App\Filament\Tables\Columns\CustomIcon;
use App\Models\Service;
use App\Services\ServiceService;
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
use Filament\Forms\Components\RichEditor;
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
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Illuminate\Support\Str;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;

class ServiceResource extends Resource
{
    use SoftDeletes, Translatable;

    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getPluralModelLabel(): string
    {
        return __('system.service.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.service.model');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make(__('system.field.info'))
                        ->icon(Heroicon::OutlinedInformationCircle)
                        ->schema([
                            IconSelect::make('icon')
                                ->required()
                                ->columnSpan(4)
                                ->label(__('system.field.icon')),

                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(4)
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

                            Textarea::make('description')
                                ->required()
                                ->rows(3)
                                ->maxLength(255)
                                ->columnSpanFull()
                                ->label(__('system.field.description')),


                            RichEditor::make('content')
                                ->required()
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
                                ->fileAttachmentsDirectory('services/content')
                                ->columnSpanFull()
                                ->label(__('system.field.content')),
                        ])->columns(12),

                    Step::make(__('system.field.gallery'))
                        ->icon(Heroicon::OutlinedPhoto)
                        ->visible(fn(Get $get): bool => $get('has_detail_page'))
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->imageEditor()
                                ->default('default.webp')
                                ->disk('images')
                                ->directory('services')
                                ->maxSize(2048)
                                ->columnSpanFull()
                                ->label(__('system.field.image')),
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
            ->afterReordering(fn (array $order) => app(ServiceService::class)->flush())
            ->columns([
                ImageColumn::make('image')
                    ->disk('images')
                    ->imageSize(100)
                    ->label(__('system.field.image')),

                CustomIcon::make('icon')
                    ->label(__('system.field.icon')),

                TextColumn::make('title')
                    ->description(fn(Service $record): ?string => Str::limit($record->description, 50))
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
            'index' => ManageServices::route('/'),
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
