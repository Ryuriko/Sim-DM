<?php

namespace App\Filament\Resources\CompanProfileResource\Pages;

use App\Filament\Resources\CompanProfileResource;
use Filament\Resources\Pages\Page;

class CustomPage extends Page
{
    protected static string $resource = CompanProfileResource::class;

    protected static string $view = 'filament.resources.compan-profile-resource.pages.custom-page';
}
