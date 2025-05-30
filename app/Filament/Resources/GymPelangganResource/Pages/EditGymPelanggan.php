<?php

namespace App\Filament\Resources\GymPelangganResource\Pages;

use App\Filament\Resources\GymPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGymPelanggan extends EditRecord
{
    protected static string $resource = GymPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
