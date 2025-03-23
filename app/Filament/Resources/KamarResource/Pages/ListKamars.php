<?php

namespace App\Filament\Resources\KamarResource\Pages;

use App\Models\Foto;
use App\Models\Kamar;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\KamarResource;
use Filament\Resources\Pages\ListRecords;

class ListKamars extends ListRecords
{
    protected static string $resource = KamarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, string $model): Model {
                    unset($data['fasilitas']);
                    unset($data['fotos']);

                    return $model::create($data);
                })
                ->after(function ($data, $record) {
                    $fasilitas = $data['fasilitas'];
                    $fotos = $data['fotos'];
                    foreach ($fotos as $foto) {
                        $createFoto = Foto::create([
                            'path' => $foto
                        ]);

                        $record->fotos()->snyc($createFoto->id);
                    }

                    $record->fasilitas()->sync($fasilitas);
                }),
        ];
    }
}
