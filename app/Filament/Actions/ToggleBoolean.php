<?php

namespace App\Filament\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class ToggleBoolean extends Action
{
    protected string $servicePath;

    protected string $field;

    protected string $method;

    protected ?string $maxMethod = null;

    protected string $policyAbility = 'toggle';

    protected ?Closure $visibilityCondition = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->name('toggleBoolean');
        $this->iconButton();
        $this->requiresConfirmation();

        $this->visible(function (Model $record): bool {
            return $this->visibilityCondition
                ? ($this->visibilityCondition)($record)
                : true;
        });

        $this->icon(fn (Model $record) =>
            $record->{$this->field}
                ? 'heroicon-o-eye-slash'
                : 'heroicon-o-eye'
        );

        $this->color(fn (Model $record) =>
            $record->{$this->field}
                ? 'danger'
                : 'success'
        );

        $this->modalHeading(fn (Model $record): string =>
            $record->{$this->field}
                ? __('system.action.hide')
                : __('system.action.show')
        );

        $this->modalDescription(fn (Model $record): string =>
            $record->{$this->field}
                ? __('system.message.confirm_hide')
                : __('system.message.confirm_show')
        );

        $this->modalSubmitActionLabel(fn (Model $record): string =>
            $record->{$this->field}
                ? __('system.action.hide')
                : __('system.action.show')
        );

        $this->authorize(
            fn (Model $record) =>
                auth()->user()->can($this->policyAbility, $record)
        );

        $this->action(function (Model $record) {
            $service = app($this->servicePath);

            $success = $service->{$this->method}(
                $record,
                ! $record->{$this->field}
            );

            if (! $success && $this->maxMethod) {
                Notification::make()
                    ->title(
                        __('validation.max_visible')
                        . ' '
                        . $service->{$this->maxMethod}()
                    )
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

    public function field(string $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function service(string $servicePath): static
    {
        $this->servicePath = $servicePath;

        return $this;
    }

    public function method(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function maxMethod(?string $maxMethod): static
    {
        $this->maxMethod = $maxMethod;

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
