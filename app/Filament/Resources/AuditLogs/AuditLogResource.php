<?php

namespace App\Filament\Resources\AuditLogs;

use App\Enums\AuditLogAction;
use App\Filament\Resources\AuditLogs\Pages\ManageAuditLogs;
use App\Models\AuditLog;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    // protected static ?string $recordTitleAttribute = 'action';

    public static function getPluralModelLabel(): string
    {
        return __('system.audit_logs.navigation');
    }
    public static function getModelLabel(): string
    {
        return __('system.audit_logs.model');
    }

    public static function getNavigationGroup(): string
    {
        return __('system.settings.group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('action')->badge()->size(TextSize::Large)->inlineLabel()->columnSpan(3)->label(__('system.field.action')),
                TextEntry::make('user.name')->inlineLabel()->columnSpan(3)->label(__('system.field.user')),

                TextEntry::make('subject_type')
                    ->inlineLabel()
                    ->formatStateUsing(fn ($state): string => __('system.model.'.str($state)->classBasename()->snake()))
                    ->columnSpan(3)
                    ->label(__('system.field.subject')),

                TextEntry::make('subject_id')->inlineLabel()->columnSpan(3)->label(__('system.field.id')),

                Repeater::make('changes')
                    ->table(fn (Get $get): array => [
                        TableColumn::make(__('system.field.key')),
                        ...($get('action') === AuditLogAction::Delete ? [TableColumn::make(__('system.field.value'))] : []),

                        ...($get('action') === AuditLogAction::Update ? [
                            TableColumn::make(__('system.field.old')),
                            TableColumn::make(__('system.field.new')),
                        ] : []),

                    ])
                    ->schema(fn (Get $get): array => [
                        TextEntry::make('key')->formatStateUsing(fn ($state) => __("system.field.{$state}")),

                        ...($get('action') === AuditLogAction::Delete ? [TextEntry::make('value')] : []),

                        ...($get('action') === AuditLogAction::Update ? [
                            TextEntry::make('old'),
                            TextEntry::make('new'),
                        ] : []),
                    ])
                    ->hiddenLabel()
                    ->columnSpanFull(),

                TextEntry::make('date')->dateTime()->sinceTooltip()->alignCenter()->hiddenLabel()->columnSpanFull()->label(__('system.field.date')),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->deferLoading()
            ->columns([
                TextColumn::make('action')->badge()->size(TextSize::Large)->label(__('system.field.action')),
                TextColumn::make('user.name')->placeholder(__('system.field.system'))->label(__('system.field.user')),

                TextColumn::make('subject_type')->formatStateUsing(fn($state): string => __('system.model.' . str($state)->classBasename()->snake()))->label(__('system.field.subject_type')),
                TextColumn::make('subject_id')->label(__('system.field.subject_id')),

                TextColumn::make('date')->since()->dateTimeTooltip()->sortable()->label(__('system.field.date')),
            ])
            ->filters([
                DateRangeFilter::make('date')->defaultThisYear()->label(__('system.field.date')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->iconButton()
                    ->modalWidth(Width::SixExtraLarge)
                    ->hidden(fn($record) => \in_array($record->action, [AuditLogAction::Create, AuditLogAction::Restore])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAuditLogs::route('/'),
        ];
    }
}
