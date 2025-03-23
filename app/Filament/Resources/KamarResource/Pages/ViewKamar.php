<?php

namespace App\Filament\Resources\KamarResource\Pages;

use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\KamarResource;

class ViewKamar extends ViewRecord
{
    protected static string $resource = KamarResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('no')
                    ->label('No Kamar'),
                Infolists\Components\TextEntry::make('tipe.nama')
                    ->label('Tipe Kamar'),
                Infolists\Components\TextEntry::make('lantai')
                    ->label('Lantai'),
                Infolists\Components\TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'terisi' => 'gray',
                        'dibersihkan' => 'warning',
                        'tidak tersedia' => 'danger',
                    }),
                Infolists\Components\TextEntry::make('fasilitas.nama')
                    ->label('Fasilitas')
                    ->listWithLineBreaks()
                    ->bulleted(),
                Infolists\Components\ImageEntry::make('foto')
                    ->label('Thumbnail'),
                Infolists\Components\ImageEntry::make('fotos.path')
                    ->label('Foto')
                    ->height(100)
                    ->stacked()
                    ->ring(0)
            ]);
    }
}
