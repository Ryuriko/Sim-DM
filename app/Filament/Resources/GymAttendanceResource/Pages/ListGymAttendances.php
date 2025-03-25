<?php

namespace App\Filament\Resources\GymAttendanceResource\Pages;

use App\Filament\Resources\GymAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGymAttendances extends ListRecords
{
    protected static string $resource = GymAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
