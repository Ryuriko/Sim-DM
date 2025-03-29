<?php

namespace App\Filament\Resources\CutiKaryawanResource\Pages;

use App\Filament\Resources\CutiKaryawanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCutiKaryawan extends EditRecord
{
    protected static string $resource = CutiKaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
