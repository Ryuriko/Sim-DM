<?php

namespace App\Filament\Resources\QrVerificationResource\Pages;

use App\Filament\Resources\QrVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQrVerification extends EditRecord
{
    protected static string $resource = QrVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
