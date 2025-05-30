<?php

namespace App\Filament\Resources\GymSubscriptionResource\Pages;

use Filament\Actions;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GymSubscriptionResource;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ListGymSubscriptions extends ListRecords
{
    protected static string $resource = GymSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat')
                ->modalHeading('Reseravasi Hotel')
                ->modalSubmitActionLabel('Buat')
                ->mutateFormDataUsing(function (array $data) {
                    $data['tgl_selesai'] = Carbon::parse($data['tgl_mulai'])->copy()->addMonth();

                    return $data;
                })
                ->after(function ($record, $data) {
                    $price = 125000;
                    $qty = 1;

                    $transaksi_arr['orderId'] = random_int(1, 9999999999);
                    $transaksi_arr['reference'] = Str::random(24);
                    $transaksi_arr['status'] = 'ots';
                    $transaksi_arr['order_date'] = now();
                    $transaksi_arr['paid_at'] = now();
                    $transaksi_arr['used_at'] = now();
                    $transaksi_arr['tipe'] = 'gym-ots';

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
                    $record->update([
                        'transaksi_id' => $transaksi->id,
                        'status' => 'aktif',
                    ]);
                }),
        ];
    }
}
