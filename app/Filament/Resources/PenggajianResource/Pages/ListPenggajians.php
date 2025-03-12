<?php

namespace App\Filament\Resources\PenggajianResource\Pages;

use App\Filament\Resources\PenggajianResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPenggajians extends ListRecords
{
    protected static string $resource = PenggajianResource::class;

    public function getTabs(): array
    {
        return [
            'data' => Tab::make('Data'),
            'setting' => Tab::make('Setting')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('active', true)),
        ];
    }
}
