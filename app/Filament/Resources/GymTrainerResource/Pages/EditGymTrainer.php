<?php

namespace App\Filament\Resources\GymTrainerResource\Pages;

use App\Filament\Resources\GymTrainerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGymTrainer extends EditRecord
{
    protected static string $resource = GymTrainerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
