<?php

namespace App\Filament\Resources\GymAttendanceResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GymAttendanceResource;

class ListGymAttendances extends ListRecords
{
    protected static string $resource = GymAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $tgl = Session::get('filteredDate');
                    $data['tgl'] = $tgl;
            
                    return $data;
                })
        ];
    }
}
