<?php

namespace App\Filament\Resources\ReservasiResource\Pages;

use Carbon\Carbon;
use App\Models\Kamar;
use Filament\Actions;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use App\Models\ReservasiDate;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReservasiResource;
use App\Http\Controllers\Api\V1\PaymentController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ListReservasis extends ListRecords
{
    protected static string $resource = ReservasiResource::class;

    protected $kamar_id;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat')
                ->modalHeading('Reseravasi Hotel')
                ->modalSubmitActionLabel('Buat')
                ->mutateFormDataUsing(function (array $data) {
                    $this->kamar_id = $data['kamar_id'];
                    unset($data['kamar_id']);

                    return $data;
                })
                ->after(function ($record, $data) {
                    $checkin = Carbon::parse($data['checkin']);
                    $checkout = Carbon::parse($data['checkout']);
                    $price = 0;
                    $qty = 0;

                    $kamars = $this->kamar_id;
                    foreach ($kamars as $kamar) {
                        for ($date = $checkin->copy(); $date->lte($checkout->subDay()); $date->addDay()) {
                            $record->kamars()->attach($kamar, ['date' => $date->toDateString()]);
                            $price = $price + (int)Kamar::find($kamar)->tipe->harga;
                            $qty++;
                        }
                    }

                    $transaksi_arr['orderId'] = random_int(1, 9999999999);
                    $transaksi_arr['reference'] = Str::random(24);
                    $transaksi_arr['status'] = 'ots';
                    $transaksi_arr['order_date'] = now();
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
                        ->generate('reservasi-ots ' . $transaksi['reference']. ' ' .  $transaksi['orderId'], $fullPath);
                        
                    $transaksi->update(['qrcode' => $qrCodePath]);
                    $record->update(['transaksi_id' => $transaksi->id]);
                })
        ];
    }
}
