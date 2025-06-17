<?php

namespace App\Filament\Resources\ParkirPelangganResource\Pages;

use App\Filament\Resources\ParkirPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParkirPelanggan extends EditRecord
{
    protected static string $resource = ParkirPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
