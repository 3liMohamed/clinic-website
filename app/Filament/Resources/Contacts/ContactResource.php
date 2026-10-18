<?php

namespace App\Filament\Resources\Contacts;

use App\Filament\Resources\Contacts\Pages\ManageContacts;
use App\Models\Contact;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $recordTitleAttribute = 'email';

    public static function getPluralModelLabel(): string
    {
        return __('system.contact.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.contact.model');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('system.inquiries.group');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Contact::query()->whereNull('read_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextEntry::make('name')
                    ->copyable()
                    ->label(__('system.field.name')),

                TextEntry::make('email')
                    ->copyable()
                    ->label(__('system.field.email')),

                TextEntry::make('phone')
                    ->copyable()
                    ->label(__('system.field.phone')),

                TextEntry::make('sent_at')
                    ->dateTime()
                    ->label(__('system.field.send_at')),

                TextEntry::make('subject')
                    ->label(__('system.field.subject')),

                TextEntry::make('message')
                    ->label(__('system.field.message'))
                    ->placeholder('-')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('email')
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label(__('system.field.name')),

                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->label(__('system.field.email')),

                TextColumn::make('phone')
                    ->searchable()
                    ->copyable()
                    ->label(__('system.field.phone')),

                TextColumn::make('subject')
                    ->searchable()
                    ->limit(40)
                    ->label(__('system.field.subject')),

                TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('system.field.send_at')),

                TextColumn::make('read_at')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? __('system.field.read') : __('system.field.unread'))
                    ->color(fn($state) => $state ? 'success' : 'warning')
                    ->label(__('system.field.read_at')),
            ])

            ->recordActions([
                ViewAction::make()->iconButton(),
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
            'index' => ManageContacts::route('/'),
        ];
    }
}
