<?php

namespace App\Filament\Resources\GymSubscriptionResource\Pages;

use App\Filament\Resources\GymSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGymSubscription extends EditRecord
{
    protected static string $resource = GymSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
