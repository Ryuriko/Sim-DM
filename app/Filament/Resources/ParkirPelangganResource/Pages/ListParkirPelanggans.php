<?php

namespace App\Filament\Resources\ParkirPelangganResource\Pages;

use Filament\Actions;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Filament\Resources\ParkirPelangganResource;

class ListParkirPelanggans extends ListRecords
{
    protected static string $resource = ParkirPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Beli')
                ->modalHeading('Beli Tiket Parkir')
                ->modalDescription('Harga satu tiket parkir adalah Rp. 5.000 -,')
                ->modalSubmitActionLabel('Lanjutkan Ke Pembayaran')
                ->mutateFormDataUsing(function (array $data) {
                    $data['user_id'] = auth()->user()->id;

                    return $data;
                })
                ->successRedirectUrl(function (Model $record) {
                    try {
                        $paymentController = new PaymentController;
    
                        $user = auth()->user();
                        $paymentAmount = 5000;
                        $productDetails = 'Pembelian Tiket Parkir DM Tirta Persada';
                        $email = $user->email;
                        $customerVaName = $user->name;
                        $itemName = 'Tiket Parkir DM Tirta Persada';
                        $itemPrice = $paymentAmount;
                        $itemQty = 1;

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
                                'tipe' => 'parkir',
                            ]);
                            $record->update([
                                'transaksi_id' => $transaksi->id
                            ]);
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
    
                        return $transaksi->paymentUrl;
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
