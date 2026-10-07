<?php

namespace App\Filament\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class MakeVisible extends Action
{
    protected string $servicePath;
    protected string $policyAbility = 'visibility';
    protected ?Closure $visibilityCondition = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->name('makeVisible');
        $this->iconButton();
        $this->requiresConfirmation();


        $this->visible(function (Model $record): bool {
            if ($record->is_visible) {
                return false;
            }

            return $this->visibilityCondition
                ? ($this->visibilityCondition)($record)
                : true;
        });


        $this->authorize(fn(Model $record) => auth()->user()->can($this->policyAbility, $record));

        $this->icon('heroicon-o-eye');
        $this->color('success');

        $this->modalHeading(__('system.action.show'));

        $this->modalDescription(__('system.message.confirm_show'));

        $this->modalSubmitActionLabel(__('system.action.show'));


        $this->action(function (Model $record) {
            $service = app($this->servicePath);

            $success = $service->makeVisible($record, ! $record->is_visible);

            if ($success) {
                Notification::make()
                    ->title(__('system.notification.updated'))
                    ->success()
                    ->send();
            }
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
