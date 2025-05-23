<?php

namespace App\Filament\Resources\TicketWaterboomResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use App\Export\TicketExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\ListRecords;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Filament\Resources\TicketWaterboomResource;

class ListTicketWaterbooms extends ListRecords
{
    protected static string $resource = TicketWaterboomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Export')
                ->action(function() {
                    $bulan = Session::get('bulan');
                    $tahun = Session::get('tahun');
                    $fileName = $bulan . '-' . $tahun . '.xlsx';
                    
                    return Excel::download(new TicketExport($bulan, $tahun), $fileName, \Maatwebsite\Excel\Excel::XLSX);
                }),
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data) {
                    $data['orderId'] = random_int(1, 9999999999);
                    $data['reference'] = Str::random(24);
                    $data['status'] = 'ots';
                    $data['paid_at'] = now();
                    $data['used_at'] = now();

                    return $data;
                })
                ->after(function($record) {
                    $qrCodePath = 'qrcodes/' . $record['id'] . '.png';
                    $fullPath = storage_path('app/public/' . $qrCodePath);

                    if (!file_exists(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0755, true);
                    }

                    QrCode::format('png')
                        ->size(250)
                        ->generate($record['reference']. ' ' .  $record['orderId'], $fullPath);

                    $record->update(['qrcode' => $qrCodePath]);
                }),
        ];
    }
}
