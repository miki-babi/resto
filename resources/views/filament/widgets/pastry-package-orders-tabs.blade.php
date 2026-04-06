@props([
    'tabs',
    'activeTab',
])

<x-filament::tabs class="fi-ta-tabs">
    @foreach ($tabs as $key => $tab)
        <x-filament::tabs.item
            :active="$activeTab === $key"
            wire:click="$set('activeTab', @js($key))"
            type="button"
        >
            {{ $tab->getLabel() }}
        </x-filament::tabs.item>
    @endforeach
</x-filament::tabs>
