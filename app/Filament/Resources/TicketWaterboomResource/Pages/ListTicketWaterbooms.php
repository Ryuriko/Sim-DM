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
use App\Models\Transaksi;

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
                ->after(function($record) {
                    $transaksi_arr['orderId'] = random_int(1, 9999999999);
                    $transaksi_arr['reference'] = Str::random(24);
                    $transaksi_arr['status'] = 'ots';
                    $transaksi_arr['paid_at'] = now();
                    $transaksi_arr['used_at'] = now();
                    $transaksi_arr['tipe'] = 'ticket-ots';

                    $transaksi = Transaksi::create($transaksi_arr);
                    
                    $qrCodePath = 'qrcodes/' . $record['id'] . '.png';
                    $fullPath = storage_path('app/public/' . $qrCodePath);

                    if (!file_exists(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0755, true);
                    }

                    QrCode::format('png')
                        ->size(250)
                        ->generate($transaksi['reference']. ' ' .  $transaksi['orderId'], $fullPath);
                        
                    $transaksi->update(['qrcode' => $qrCodePath]);
                    $record->update(['transaksi_id' => $transaksi->id]);
                }),
        ];
    }
}
