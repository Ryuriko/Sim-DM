<?php

namespace App\Filament\Resources\TicketWaterboomResource\Pages;

use App\Filament\Resources\TicketWaterboomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketWaterboom extends EditRecord
{
    protected static string $resource = TicketWaterboomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
