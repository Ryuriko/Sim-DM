<x-filament-panels::page>
   <x-filament::tabs>
      <x-filament::tabs.item 
      name="data" 
      :active="$this->activeTab === 'data'" 
      wire:click="$set('activeTab', 'data')">
      Data
      </x-filament::tabs.item>

      <x-filament::tabs.item 
      name="kategori" 
      :active="$this->activeTab === 'kategori'" 
      wire:click="$set('activeTab', 'kategori')">
      Kategori
      </x-filament::tabs.item>
   </x-filament::tabs>

   @if ($this->activeTab == 'data' || $this->activeTab != 'kategori')
      {{ $this->table }}
   @elseif ($this->activeTab == 'kategori')
      @livewire('kategori-barang')
   @endif
</x-filament-panels::page>
