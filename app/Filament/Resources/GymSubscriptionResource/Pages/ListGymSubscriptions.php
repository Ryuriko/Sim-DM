<?php

namespace App\Filament\Resources\GymSubscriptionResource\Pages;

use App\Filament\Resources\GymSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGymSubscriptions extends ListRecords
{
    protected static string $resource = GymSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
