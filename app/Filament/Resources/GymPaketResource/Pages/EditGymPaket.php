<?php

namespace App\Filament\Resources\GymPaketResource\Pages;

use App\Filament\Resources\GymPaketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGymPaket extends EditRecord
{
    protected static string $resource = GymPaketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
