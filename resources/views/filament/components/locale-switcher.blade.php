@php
    $locals = [
        'ar' => [
            'label' => __('locales.arabic'),
            'flag' => 'eg',
        ],
        'en' => [
            'label' => __('locales.english'),
            'flag' => 'gb',
        ]
    ];
@endphp

<x-filament::dropdown placement="bottom-start">
    <x-slot name="trigger">
        <x-filament::avatar size="md" src="https://cdn.jsdelivr.net/gh/hampusborgos/country-flags@main/svg/{{$locals[app()->getLocale()]['flag'] ?: null}}.svg" />
    </x-slot>

    @foreach ($locals as $key => $language)
        <x-filament::dropdown.list.item :image="'https://cdn.jsdelivr.net/gh/hampusborgos/country-flags@main/svg/' . $language['flag'] . '.svg'" tag="a" :spaMode="false" :disabled="app()->getLocale() === $key" :href="route('locale', ['locale' => $key])">
            <span @style(['font-weight: 600;' => app()->getLocale() === $key])>{{ $language['label'] }}</span>
        </x-filament::dropdown.list.item>
    @endforeach
</x-filament::dropdown>
