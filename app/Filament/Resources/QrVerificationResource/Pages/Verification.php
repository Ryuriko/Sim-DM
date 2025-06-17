<?php

namespace App\Filament\Resources\QrVerificationResource\Pages;

use App\Filament\Resources\QrVerificationResource;
use Filament\Resources\Pages\Page;

class Verification extends Page
{
    protected static string $resource = QrVerificationResource::class;

    protected static string $view = 'filament.resources.qr-verification-resource.pages.verification';
}
