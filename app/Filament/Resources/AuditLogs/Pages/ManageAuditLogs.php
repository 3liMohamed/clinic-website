<?php

namespace App\Filament\Resources\AuditLogs\Pages;

use App\Enums\AuditLogAction;
use App\Filament\Resources\AuditLogs\AuditLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;


class ManageAuditLogs extends ManageRecords
{
    protected static string $resource = AuditLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        abort_unless(isset($this->getCachedTabs()[$this->activeTab]), 404);
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make(__('system.field.all')),
        ];

        foreach (AuditLogAction::cases() as $action) {
            $tabs[$action->value] = Tab::make($action->getLabel())
                ->badgeColor($action->getColor())
                ->modifyQueryUsing(
                    fn(Builder $query) => $query->where('action', $action->value),
                );
        }

        return $tabs;
    }
}
