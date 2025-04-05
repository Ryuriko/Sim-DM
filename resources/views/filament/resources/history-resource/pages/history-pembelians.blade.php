<x-filament-panels::page>
   <x-filament::tabs>
      <x-filament::tabs.item 
      name="penggunaan" 
      :active="$this->activeTab === 'penggunaan'" 
      wire:click="$set('activeTab', 'penggunaan')">
      Penggunaan
      </x-filament::tabs.item>

      <x-filament::tabs.item 
      name="pembelian" 
      :active="$this->activeTab === 'pembelian'" 
      wire:click="$set('activeTab', 'pembelian')">
      Pembelian
      </x-filament::tabs.item>
   </x-filament::tabs>

   @if ($this->activeTab == 'data' || $this->activeTab != 'pembelian')
      {{ $this->table }}
   @elseif ($this->activeTab == 'pembelian')
      @livewire('history-pembelian')
   @endif
</x-filament-panels::page>
