<x-container>
    <form wire:submit="create">
        <div class="flex justify-end">
            <div class="fixed top-20 ">
                <x-filament::button type="submit" icon="heroicon-m-folder-plus">
                    Registrar Tramite
                </x-filament::button>
            </div>
        </div>
        <div class="mt-4">
            {{ $this->form }}
        </div>
    </form>
    <x-filament-actions::modals />
</x-container>
