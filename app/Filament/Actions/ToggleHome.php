<?php

namespace App\Filament\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class ToggleHome extends Action
{
    protected string $servicePath;
    protected string $policyAbility = 'toggleHome';
    protected ?Closure $visibilityCondition = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->name('toggleIsHome');
        $this->iconButton();
        $this->requiresConfirmation();

        $this->visible(function (Model $record): bool {
            if (! $record->is_visible) {
                return false;
            }

            return $this->visibilityCondition
                ? ($this->visibilityCondition)($record)
                : true;
        });
        $this->icon(fn(Model $record) => $record->is_home ? 'heroicon-o-eye-slash' : 'heroicon-o-eye');
        $this->color(fn(Model $record) => $record->is_home ? 'danger' : 'success');

        $this->modalHeading(fn(Model $record): string => $record->is_home
            ? __('system.action.hide')
            : __('system.action.show'));

        $this->modalDescription(fn(Model $record): string => $record->is_home
            ? __('system.message.confirm_hide')
            : __('system.message.confirm_show'));

        $this->modalSubmitActionLabel(fn(Model $record): string => $record->is_home
            ? __('system.action.hide')
            : __('system.action.show'));

        $this->authorize(fn(Model $record) => auth()->user()->can($this->policyAbility, $record));

        $this->action(function (Model $record) {
            $service = app($this->servicePath);

            $success = $service->toggleHome($record, ! $record->is_home);

            if (! $success) {
                Notification::make()
                    ->title(__('validation.max_visible') . ' ' . $service->maxHome())
                    ->danger()
                    ->send();

                return;
            }

            Notification::make()
                ->title(__('system.notification.updated'))
                ->success()
                ->send();
        });
    }

    public function service(string $servicePath): static
    {
        $this->servicePath = $servicePath;

        return $this;
    }

    public function policyAbility(string $ability): static
    {
        $this->policyAbility = $ability;

        return $this;
    }

    public function visibleWhen(Closure $condition): static
    {
        $this->visibilityCondition = $condition;

        return $this;
    }
}
