<?php

namespace App\Filament\Resources\GymAttendanceResource\Pages;

use App\Filament\Resources\GymAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGymAttendance extends EditRecord
{
    protected static string $resource = GymAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
