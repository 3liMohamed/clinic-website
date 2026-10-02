<?php

namespace App\Filament\Resources\Blogs;

use App\Filament\Actions\ToggleHome;
use App\Filament\Resources\Blogs\Pages\ManageBlogs;
use App\Models\Blog;
use App\Services\BlogService;
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
use Filament\Actions\ReplicateAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class BlogResource extends Resource
{
    use Translatable, SoftDeletes;

    protected static ?string $model = Blog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getPluralModelLabel(): string
    {
        return __('system.blog.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('system.blog.model');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make(__('system.field.info'))
                        ->icon(Heroicon::OutlinedInformationCircle)
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->imageEditor()
                                ->default('default.webp')
                                ->disk('images')
                                ->directory('blogs')
                                ->maxSize(2048)
                                ->columnSpanFull()
                                ->label(__('system.field.image')),


                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(8)
                                ->label(__('system.field.title')),


                            Toggle::make('is_visible')
                                ->live()
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->columnSpan(2)
                                ->label(__('system.field.is_visible'))
                                ->afterStateUpdated(function (Set $set, bool $state): void {
                                    if (! $state) {
                                        $set('has_detail_page', false);
                                    }
                                }),

                            Toggle::make('has_detail_page')
                                ->live()
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->columnSpan(2)
                                ->disabled(fn(Get $get): bool => ! $get('is_visible'))
                                ->dehydrateStateUsing(fn(Get $get, bool $state): bool => $get('is_visible') ? $state : false)
                                ->label(__('system.field.has_detail_page')),

                            Textarea::make('description')
                                ->required()
                                ->rows(3)
                                ->maxLength(255)
                                ->minLength(3)
                                ->columnSpanFull()
                                ->label(__('system.field.description')),

                        ])->columns(12),

                    Step::make(__('system.field.content_info'))
                        ->icon(Heroicon::OutlinedDocumentText)
                        ->visible(fn(Get $get): bool => $get('has_detail_page'))
                        ->schema([
                            RichEditor::make('content')
                                ->required()
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
                                    'blockquote',
                                    'attachFiles',
                                    'undo',
                                    'redo',
                                ])
                                ->resizableImages()
                                ->fileAttachmentsDisk('images')
                                ->fileAttachmentsDirectory('blogs/content')
                                ->columnSpanFull()
                                ->label(__('system.field.content')),
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
            ->defaultSort('order')
            ->reorderable('order')
            ->recordTitleAttribute('title')
            ->afterReordering(fn(array $order) => app(BlogService::class)->flush())
            ->columns([
                ImageColumn::make('image')
                    ->disk('images')
                    ->imageSize(100)
                    ->label(__('system.field.image')),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(
                        fn(Blog $record): string => str($record->description)->limit(80)
                    )
                    ->label(__('system.field.title')),

                TextColumn::make('user.name')
                    ->sortable()
                    ->label(__('system.field.user')),

                TextColumn::make('published_at')
                    ->date()
                    ->sortable()
                    ->label(__('system.field.published_at')),

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
                ToggleHome::make()
                    ->service(BlogService::class)
                    ->visibleWhen(fn (Blog $record): bool => $record->is_visible && $record->published_at)
                    ->policyAbility('toggleHome'),

                Action::make('publish')
                    ->visible(fn(Blog $record): bool => $record->is_visible)
                    ->authorize(fn(Blog $record) => auth()->user()->can('publish', $record))
                    ->icon(fn(Blog $record): Heroicon => $record->published_at ? Heroicon::OutlinedXMark : Heroicon::OutlinedMegaphone)
                    ->color(fn(Blog $record): string => $record->published_at ? 'danger' : 'success')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->modalHeading(fn(Blog $record): string => $record->published_at ? __('system.action.unpublish') : __('system.action.publish'))
                    ->modalDescription(fn(Blog $record): string => $record->published_at ? __('system.message.confirm_unpublish') : __('system.message.confirm_publish'))
                    ->modalSubmitActionLabel(fn(Blog $record): string => $record->published_at ? __('system.action.unpublish') : __('system.action.publish'))
                    ->action(function (Blog $record): void {
                        $record->update(['published_at' => $record->published_at ? null : now()]);
                        Notification::make()
                            ->title(__('system.notification.updated'))
                            ->success()
                            ->send();
                    }),
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
            'index' => ManageBlogs::route('/'),
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
