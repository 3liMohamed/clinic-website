<?php

namespace App\Filament\Resources\WhyChooses;

use App\Filament\Resources\WhyChooses\Pages\ManageWhyChooses;
use App\Models\WhyChoose;
use App\Rules\ValidOrHash;
use App\Services\WhyChooseService;
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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
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

class WhyChooseResource extends Resource
{
    use Translatable, SoftDeletes;

    public static function getPluralModelLabel(): string
    {
        return __('system.why_choose.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.why_choose.model');
    }

    protected static ?string $model = WhyChoose::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make(__('system.section.basic_information'))
                    ->collapsed()
                    ->schema([
                        FileUpload::make('image')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->disk('images')
                            ->directory('whyChooses')
                            ->maxSize(2048)
                            ->columnSpanFull()
                            ->label(__('system.field.image')),

                        TextInput::make('title')
                            ->required()
                            ->columnSpan(6)
                            ->label(__('system.field.title')),

                        TextInput::make('video_url')
                            ->required()
                            ->rule(ValidOrHash::urlOrHash())
                            ->columnSpan(6)
                            ->label(__('system.field.video_url')),
                    ])
                    ->columns(12)
                    ->columnSpanFull(),

                Section::make(__('system.section.content'))
                    ->collapsed()
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
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull()
                            ->label(__('system.field.content')),

                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                TextInput::make('text')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(8)
                                    ->label(__('system.field.text')),

                                Select::make('column')
                                    ->options([
                                        'left' => __('system.option.left'),
                                        'right' => __('system.option.right'),
                                    ])
                                    ->required()
                                    ->default('left')
                                    ->columnSpan(4)
                                    ->label(__('system.field.column')),
                            ])
                            ->defaultItems(0)
                            ->columns(12)
                            ->columnSpanFull()
                            ->orderColumn('order')
                            ->reorderableWithButtons()
                            ->collapsed()
                            ->label(__('system.field.items')),
                    ])
                    ->columns(12)
                    ->columnSpanFull(),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([

                ImageColumn::make('image')
                    ->disk('images')
                    ->imageSize(100)
                    ->square()
                    ->label(__('system.field.image')),

                TextColumn::make('title')
                    ->searchable()
                    ->label(__('system.field.title')),

                TextColumn::make('items_count')
                    ->counts(['items' => fn ($query) => $query->withTrashed()])
                    ->sortable()
                    ->label(__('system.field.items')),

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
                Action::make('toggleVisibility')
                    ->authorize(fn(WhyChoose $record) => auth()->user()->can('toggleVisibility', $record))
                    ->label(fn(WhyChoose $record) => $record->is_visible ? __('system.action.hide') : __('system.action.show'))
                    ->icon(fn(WhyChoose $record) => $record->is_visible ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn(WhyChoose $record) => $record->is_visible ? 'danger' : 'success')
                    ->action(function (WhyChoose $record, WhyChooseService $whyChooseService) {

                        $success = $whyChooseService->toggleVisibility($record, ! $record->is_visible);

                        if ($success) {
                            Notification::make()
                                ->title(__('system.notification.updated'))
                                ->success()
                                ->send();
                        }
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
            'index' => ManageWhyChooses::route('/'),
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
