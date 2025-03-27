<?php

namespace App\Filament\Resources\CompanyProfileResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\CompanyProfileResource;

class CustomPageCompanyProfile extends Page
{
    protected static string $resource = CompanyProfileResource::class;

    protected static string $view = 'filament.resources.company-profile-resource.pages.custom-page-company-profile';
}
