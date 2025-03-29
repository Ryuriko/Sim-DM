<?php

namespace App\Filament\Resources\AbsenKaryawanResource\Pages;

use App\Filament\Resources\AbsenKaryawanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAbsenKaryawan extends EditRecord
{
    protected static string $resource = AbsenKaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
