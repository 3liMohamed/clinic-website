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
        return (int) Subscriber::query()->whereNull('read_at')->count();
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('email')
            ->columns([
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.fields.email')),

                TextColumn::make('subscribed_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('system.fields.subscribed_at')),

                TextColumn::make('read_at')
                    ->dateTime()
                    ->placeholder(__('system.status.unread'))
                    ->sortable()
                    ->label(__('system.fields.read_at')),

                TextColumn::make('status')
                    ->label(__('system.fields.status'))
                    ->state(fn(Subscriber $record): string => $record->read_at ?  __('system.status.read') : __('system.status.unread'))
                    ->badge()
                    ->color(fn(Subscriber $record): string => $record->read_at ? 'success' : 'warning'),
            ])
            ->filters([
                SelectFilter::make('read_at')
                    ->options([
                        'unread' => __('system.status.unread'),
                        'read' => __('system.status.read'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'unread' => $query->whereNull('read_at'),
                            'read' => $query->whereNotNull('read_at'),
                            default => $query,
                        };
                    })
                    ->label(__('system.fields.status'))
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
