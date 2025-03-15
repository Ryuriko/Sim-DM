<x-filament-panels::page>
   <x-filament::tabs>
      <x-filament::tabs.item 
      name="data" 
      :active="$this->activeTab === 'data'" 
      wire:click="$set('activeTab', 'data')">
      Data
      </x-filament::tabs.item>

      <x-filament::tabs.item 
      name="setting" 
      :active="$this->activeTab === 'setting'" 
      wire:click="$set('activeTab', 'setting')">
      Setting
      </x-filament::tabs.item>
   </x-filament::tabs>

   @if ($this->activeTab == 'data' || $this->activeTab != 'setting')
      {{ $this->table }}
   @elseif ($this->activeTab == 'setting')
      @livewire('salary-role-penggajian')
      <br>
      @livewire('setting-penggajian')
   @endif
</x-filament-panels::page>
