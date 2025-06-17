<?php

namespace App\Filament\Resources\ReservasiPelangganResource\Pages;

use App\Filament\Resources\ReservasiPelangganResource;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Models\Kamar;
use App\Models\Transaksi;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListReservasiPelanggans extends ListRecords
{
    protected static string $resource = ReservasiPelangganResource::class;

    protected $kamar_id;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Reservasi')
                    ->modalHeading('Reseravasi Hotel')
                    ->modalSubmitActionLabel('Lanjutkan Ke Pembayaran')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['user_id'] = auth()->user()->id;

                        $this->kamar_id = $data['kamar_id'];
                        unset($data['kamar_id']);

                        return $data;
                    })
                    ->successRedirectUrl(function (Model $record, array $data) {
                        try {
                            $paymentController = new PaymentController;
                            
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

                            $user = auth()->user();
                            $paymentAmount = $price;
                            $productDetails = 'Pembayaran Reservasi Hotel DM Tirta Persada';
                            $email = $user->email;
                            $customerVaName = $user->name;
                            $itemName = 'Reservasi Kamar Hotel DM Tirta Persada';
                            $itemPrice = $paymentAmount;
                            $itemQty = $qty;

                            $result = $paymentController->pay(
                                $paymentAmount,
                                $productDetails,
                                $email,
                                $customerVaName,
                                $itemName,
                                $itemPrice,
                                $itemQty
                            );

                            if($result['status'] == 'success') {
                                $data = $result['data'];
                                
                                $transaksi = Transaksi::create([
                                    'orderId' => $data['orderId'],
                                    'paymentUrl' => $data['paymentUrl'],
                                    'reference' => $data['reference'],
                                    'order_date' => now(),
                                    'tipe' => 'reservasi',
                                ]);
                                $record->update(['transaksi_id' => $transaksi->id]);
                            } else {
                                $message = '';
                                foreach ($result['message'] as $key => $value) {
                                    $message = $message . ' ' . $value . ' ';
                                }
                                Notification::make()
                                    ->title('Gagal')
                                    // ->body('Mohon maaf, sedang ada gangguan')
                                    ->body($message)
                                    ->danger()
                                    ->send();
                            }
        
                            return $record->transaksi->paymentUrl;
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Gagal')
                                // ->body('Mohon maaf, sedang ada gangguan')
                                ->body($th->getMessage())
                                ->danger()
                                ->send();
                        }
                    }
                ),
        ];
    }
}
