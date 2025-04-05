<x-filament-panels::page>
    {{ $this->infolist }}

    @if ($jam_masuk == null && $jam_keluar == null)
        <x-filament::button wire:click="masuk">
            Absen Masuk
        </x-filament::button>
    @elseif($jam_masuk != null && $jam_keluar == null)
        <x-filament::button wire:click="keluar" wire:confirm="Yakin ingin absen keluar?">
            Absen Keluar
        </x-filament::button>
    @else
        <div class="text-center">
            Anda telah melakukan absen untuk hari ini
        </div>
    @endif
</x-filament-panels::page>
