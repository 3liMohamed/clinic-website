<?php

namespace App\Filament\Resources\ClinicInfos;

use App\Filament\Actions\ToggleBoolean;
use App\Filament\Forms\Components\IconSelect;
use App\Filament\Resources\ClinicInfos\Pages\ManageClinicInfos;
use App\Models\ClinicInfo;
use App\Models\User;
use App\Rules\ValidOrHash;
use App\Services\ClinicInfoService;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Filament\Tables\Columns\CustomIcon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Illuminate\Support\Str;

class ClinicInfoResource extends Resource
{
    use Translatable, SoftDeletes;

    protected static ?string $model = ClinicInfo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static ?string $recordTitleAttribute = 'subtitle';

    public static function getPluralModelLabel(): string
    {
        return __('system.clinic_info.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.clinic_info.model');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                IconSelect::make('icon')
                    ->required()
                    ->columnSpan(4)
                    ->label(__('system.field.icon')),

                TextInput::make('sub_title')
                    ->required()
                    ->columnSpan(4)
                    ->label(__('system.field.sub_title')),

                TextInput::make('title')
                    ->required()
                    ->columnSpan(4)
                    ->label(__('system.field.title')),

                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull()
                    ->label(__('system.field.description')),

                TextInput::make('link_text')
                    ->required()
                    ->columnSpan(6)
                    ->label(__('system.field.link_text')),

                TextInput::make('link_url')
                    ->required()
                    ->rule(ValidOrHash::urlOrHash())
                    ->columnSpan(6)
                    ->label(__('system.field.link_url')),


            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->recordTitleAttribute('subtitle')
            ->afterReordering(fn (array $order) => app(ClinicInfoService::class)->flush())
            ->columns([
                CustomIcon::make('icon')
                    ->label(__('system.field.icon')),

                TextColumn::make('sub_title')
                    ->searchable()
                    ->label(__('system.field.sub_title')),

                TextColumn::make('title')
                    ->description(fn(ClinicInfo $record): ?string => Str::limit($record->description, 50))
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
                ToggleBoolean::make()
                    ->field('is_visible')
                    ->service(ClinicInfoService::class)
                    ->method('toggleVisibility')
                    ->maxMethod('maxVisible')
                    ->policyAbility('toggleVisibility'),

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
            'index' => ManageClinicInfos::route('/'),
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
