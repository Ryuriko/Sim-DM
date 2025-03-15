<div>
    <form wire:submit="save">
        {{ $this->form }}
        
        <br>
        <x-filament::button type="submit" class="mt-4">
            Save Changes
        </x-filament::button>
    </form>
    
    <x-filament-actions::modals />
</div>