<?php

namespace App\Filament\Resources\CutiKaryawanResource\Pages;

use App\Filament\Resources\CutiKaryawanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCutiKaryawans extends ListRecords
{
    protected static string $resource = CutiKaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
            
                    return $data;
                })
        ];
    }
}
