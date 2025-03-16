<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Resources\Pages\Page;

class KategoriBarangs extends Page
{
    protected static string $resource = BarangResource::class;

    protected static string $view = 'filament.resources.barang-resource.pages.kategori-barangs';
}
