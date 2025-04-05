<?php

namespace App\Filament\Resources\AbsenKaryawanResource\Pages;

use Filament\Actions;
use App\Models\AbsenUser;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Grid;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\AbsenKaryawanResource;
use Filament\Notifications\Notification;

class ListAbsenKaryawans extends ListRecords
{
    protected static string $resource = AbsenKaryawanResource::class;

    protected static string $view = 'filament.resources.absen-karyawan-resource.pages.absen-karyawan';

    public function getViewData(): array
    {
        $absen = AbsenUser::where('tgl', now()->toDateString())->where('user_id', auth()->user()->id)->first();
        
        if(!$absen) {
            $absen = AbsenUser::create([
                'tgl' => now()->toDateString(),
                'user_id' => auth()->user()->id
            ]);
        }

        return [
            'jam_masuk' => $absen['jam_masuk'],
            'jam_keluar' => $absen['jam_keluar'],
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record(
                AbsenUser::where('tgl', now()->toDateString())->where('user_id', auth()->user()->id)->first()
            )
            ->schema([
                Fieldset::make('Abseni ' . now()->isoFormat('dddd D MMMM YYYY'))
                    ->schema([
                        TextEntry::make('jam_masuk')
                            ->columns(2)
                            ->label('Jam Masuk')
                            ->badge()
                            ->color('success')
                            ->time(),
                        TextEntry::make('jam_keluar')
                            ->columns(2)
                            ->label('Jam Keluar')
                            ->badge()
                            ->color('success')
                            ->time()
                    ])
                    ->columns([
                        'default' => 3, // 3 kolom di layar besar
                        'md' => 3, // Tetap 3 kolom di tablet
                        'sm' => 2, // 2 kolom di layar kecil
                        'xs' => 1, // 1 kolom di layar paling kecil (jika perlu)
                    ]),

            ]);
    }

    public function masuk(): void
    {
        try {
            $absen = AbsenUser::where('tgl', now()->toDateString())->where('user_id', auth()->user()->id)->first();
    
            $absen->update([
                'status' => 'hadir',
                'jam_masuk' => now()->toTimeString()
            ]);
    
            Notification::make()
                ->success()
                ->title('Berhasil absen masuk')
                ->send();
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Gagal absen masuk')
                ->body($th->getMessage())
                ->danger()
                ->send();
        }
    }

    public function keluar(): void
    {
        try {
            $absen = AbsenUser::where('tgl', now()->toDateString())->where('user_id', auth()->user()->id)->first();
    
            $absen->update([
                'jam_keluar' => now()->toTimeString()
            ]);
    
            Notification::make()
                ->success()
                ->title('Berhasil absen keluar')
                ->send();
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Gagal absen keluar')
                ->body($th->getMessage())
                ->danger()
                ->send();
        }
    }

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
