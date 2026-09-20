<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            @if (static::canEdit())
                <x-filament::button type="submit" wire:target="save" wire:loading.attr="disabled">
                    {{ __('system.action.save') }}
                </x-filament::button>
            @endif
        </div>

    </form>

</x-filament-panels::page>
