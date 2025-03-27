<?php

namespace App\Filament\Resources\CompanyProfileResource\Pages;

use App\Filament\Resources\CompanyProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;

class ListCompanyProfiles extends ListRecords
{
    protected static string $resource = CompanyProfileResource::class;

    protected static string $view = 'filament.resources.company-profile-resource.pages.custom-page-company-profile';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
