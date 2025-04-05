<?php

namespace App\Filament\Resources\GymTrainerResource\Pages;

use App\Filament\Resources\GymTrainerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGymTrainers extends ListRecords
{
    protected static string $resource = GymTrainerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
