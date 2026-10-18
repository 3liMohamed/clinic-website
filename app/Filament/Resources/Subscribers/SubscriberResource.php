<?php

namespace App\Filament\Resources\Subscribers;

use App\Filament\Resources\Subscribers\Pages\ManageSubscribers;
use App\Models\Subscriber;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $recordTitleAttribute = 'email';

    public static function getPluralModelLabel(): string
    {
        return __('system.subscriber.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.subscriber.model');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('system.inquiries.group');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = (int) Subscriber::query()->whereNull('read_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('email')
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.field.email')),

                TextColumn::make('subscribed_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('system.field.subscribed_at')),

                TextColumn::make('read_at')
                    ->dateTime()
                    ->placeholder(__('system.status.unread'))
                    ->sortable()
                    ->label(__('system.field.read_at')),

                TextColumn::make('status')
                    ->state(fn(Subscriber $record): string => $record->read_at ?  __('system.status.read') : __('system.status.unread'))
                    ->badge()
                    ->color(fn(Subscriber $record): string => $record->read_at ? 'success' : 'warning')
                    ->label(__('system.field.status')),
            ])
            ->recordActions([
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSubscribers::route('/'),
        ];
    }
}
