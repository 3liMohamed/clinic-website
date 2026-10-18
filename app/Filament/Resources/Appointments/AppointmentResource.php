<?php

namespace App\Filament\Resources\Appointments;

use App\Filament\Resources\Appointments\Pages\ManageAppointments;
use App\Models\Appointment;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getPluralModelLabel(): string
    {
        return __('system.appointment.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.appointment.model');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('system.inquiries.group');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = (int) Appointment::query()->whereNull('read_at')->count();

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

                TextEntry::make('doctor.name')
                    ->copyable()
                    ->placeholder(__('system.status.not_assigned'))
                    ->label(__('system.field.doctor')),

                TextEntry::make('requested_at')
                    ->copyable()
                    ->dateTime()
                    ->label(__('system.field.requested_at')),

                TextEntry::make('appointment_date')
                    ->copyable()
                    ->date()
                    ->placeholder(__('system.status.not_scheduled'))
                    ->label(__('system.field.appointment_date')),

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
            ->recordTitleAttribute('name')
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.field.name')),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(__('system.field.email')),

                TextColumn::make('phone')
                    ->searchable()
                    ->label(__('system.field.phone')),

                TextColumn::make('doctor.name')
                    ->searchable()
                    ->sortable()
                    ->placeholder(__('system.status.not_assigned'))
                    ->label(__('system.field.doctor')),

                TextColumn::make('requested_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('system.field.requested_at')),

                TextColumn::make('appointment_date')
                    ->date()
                    ->placeholder(__('system.status.not_scheduled'))
                    ->sortable()
                    ->label(__('system.field.appointment_date')),

                TextColumn::make('status')
                    ->state(fn(Appointment $record): string => $record->read_at ? __('system.status.read') : __('system.status.unread'))
                    ->badge()
                    ->color(fn(Appointment $record): string => $record->read_at ? 'success' : 'warning')
                    ->label(__('system.field.status')),
            ])
            ->filters([
                SelectFilter::make('doctor_id')
                    ->multiple()
                    ->relationship('doctor', 'name')
                    ->searchable()
                    ->preload()
                    ->label(__('system.field.doctor')),
            ])
            ->recordActions([
                Action::make('set_appointment_date')
                    ->iconButton()
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->modalWidth('sm')
                    ->authorize(fn(Appointment $record): bool => auth()->user()->can('setAppointmentDate', $record))
                    ->schema(fn(Schema $schema): Schema => $schema->components([
                        DatePicker::make('appointment_date')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->minDate(fn() => now()->toDateString())
                            ->label(__('system.field.appointment_date')),
                    ]))
                    ->fillForm(fn(Appointment $record): array => [
                        'appointment_date' => $record->appointment_date?->format('Y-m-d'),
                    ])
                    ->action(function (Appointment $record, array $data): void {
                        $record->update([
                            'appointment_date' => $data['appointment_date'],
                        ]);
                    })
                    ->label(__('system.actions.set_appointment_date')),

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
            'index' => ManageAppointments::route('/'),
        ];
    }
}
