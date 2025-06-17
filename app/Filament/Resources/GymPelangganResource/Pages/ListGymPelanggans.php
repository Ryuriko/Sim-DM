<?php

namespace App\Filament\Resources\GymPelangganResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GymPelangganResource;
use App\Http\Controllers\Api\V1\PaymentController;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ListGymPelanggans extends ListRecords
{
    protected static string $resource = GymPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Berlangganan')
                ->modalHeading('Berlangganan Gym')
                ->modalSubmitActionLabel('Lanjutkan ke pembayaran')
                ->mutateFormDataUsing(function (array $data) {
                    $data['user_id'] = auth()->user()->id;
                    $data['tgl_selesai'] = Carbon::parse($data['tgl_mulai'])->copy()->addMonth();

                    return $data;
                })
                ->successRedirectUrl(function (Model $record, array $data) {
                    try {
                        $paymentController = new PaymentController;
                        
                        $price = 125000;
                        $qty = 1;

                        $user = auth()->user();
                        $paymentAmount = $price;
                        $productDetails = 'Pembayaran Berlangganan Gym DM Tirta Persada';
                        $email = $user->email;
                        $customerVaName = $user->name;
                        $itemName = 'Berlangganan Gym DM Tirta Persada';
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
                                'tipe' => 'gym',
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
                })
        ];
    }
}
