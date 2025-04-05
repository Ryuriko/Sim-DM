<?php

namespace App\Filament\Resources\AbsenKaryawanResource\Pages;

use App\Filament\Resources\AbsenKaryawanResource;
use Filament\Resources\Pages\Page;

class AbsenKaryawan extends Page
{
    protected static string $resource = AbsenKaryawanResource::class;

    protected static string $view = 'filament.resources.absen-karyawan-resource.pages.absen-karyawan';
}
