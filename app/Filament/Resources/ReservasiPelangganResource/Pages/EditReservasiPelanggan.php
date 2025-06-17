<?php

namespace App\Filament\Resources\ReservasiPelangganResource\Pages;

use App\Filament\Resources\ReservasiPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReservasiPelanggan extends EditRecord
{
    protected static string $resource = ReservasiPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
