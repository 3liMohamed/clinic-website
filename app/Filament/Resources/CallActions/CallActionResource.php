<?php

namespace App\Filament\Resources\CallActions;

use App\Filament\Resources\CallActions\Pages\ManageCallActions;
use App\Models\CallAction;
use App\Models\User;
use App\Rules\ValidOrHash;
use App\Services\CallActionService;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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

class CallActionResource extends Resource
{
    use Translatable, SoftDeletes;

    protected static ?string $model = CallAction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getPluralModelLabel(): string
    {
        return __('system.call_action.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.call_action.model');
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
                    ->directory('callAction')
                    ->maxSize(2048)
                    ->columnSpanFull()
                    ->label(__('system.field.image')),

                TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->label(__('system.field.title')),

                Textarea::make('description')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull()
                    ->label(__('system.field.description')),

                TextInput::make('primary_button_text')
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.primary_button_text')),

                TextInput::make('primary_button_url')
                    ->required()
                    ->rule(ValidOrHash::urlOrHash())
                    ->columnSpan(6)
                    ->label(__('system.field.primary_button_url')),

                TextInput::make('secondary_button_text')
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.secondary_button_text')),

                TextInput::make('secondary_button_url')
                    ->required()
                    ->rule(ValidOrHash::urlOrHash())
                    ->columnSpan(6)
                    ->label(__('system.field.secondary_button_url')),
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
                    ->description(fn(CallAction $record): ?string => Str::limit($record->description, 50))
                    ->searchable()
                    ->label(__('system.field.title')),

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
                    ->authorize(fn(CallAction $record) => auth()->user()->can('toggleVisibility', $record))
                    ->label(fn(CallAction $record) => $record->is_visible ? __('system.action.hide') : __('system.action.show'))
                    ->icon(fn(CallAction $record) => $record->is_visible ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn(CallAction $record) => $record->is_visible ? 'danger' : 'success')
                    ->action(function (CallAction $record, CallActionService $callAction) {

                        $success = $callAction->toggleVisibility($record, ! $record->is_visible);

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
            'index' => ManageCallActions::route('/'),
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
