<?php

namespace App\Filament\Resources\ParkirResource\Pages;

use Filament\Actions;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ParkirResource;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ListParkirs extends ListRecords
{
    protected static string $resource = ParkirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\Action::make('Export')
            //     ->action(function() {
            //         $bulan = Session::get('bulan');
            //         $tahun = Session::get('tahun');
            //         $fileName = $bulan . '-' . $tahun . '.xlsx';
                    
            //         return Excel::download(new ParkirExport($bulan, $tahun), $fileName, \Maatwebsite\Excel\Excel::XLSX);
            //     }),
            Actions\CreateAction::make()
                ->after(function($record) {
                    $transaksi_arr['orderId'] = random_int(1, 9999999999);
                    $transaksi_arr['reference'] = Str::random(24);
                    $transaksi_arr['status'] = 'ots';
                    $transaksi_arr['paid_at'] = now();
                    $transaksi_arr['used_at'] = now();
                    $transaksi_arr['tipe'] = 'parkir-ots';

                    $transaksi = Transaksi::create($transaksi_arr);
                    
                    $qrCodePath = 'qrcodes/' . $record['id'] . '.png';
                    $fullPath = storage_path('app/public/' . $qrCodePath);

                    if (!file_exists(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0755, true);
                    }

                    QrCode::format('png')
                        ->size(250)
                        ->generate('parkir-ots ' . $transaksi['reference']. ' ' .  $transaksi['orderId'], $fullPath);
                        
                    $transaksi->update(['qrcode' => $qrCodePath]);
                    $record->update(['transaksi_id' => $transaksi->id]);
                }),
        ];
    }
}
