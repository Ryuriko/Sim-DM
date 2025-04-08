<?php

namespace App\Filament\User\Resources\TicketResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\User\Resources\TicketResource;
use App\Http\Controllers\Api\V1\PaymentController;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Beli')
                ->modalHeading('Beli Tiket')
                ->modalDescription('Harga satu tiket adalah Rp. ' . number_format((float)env('TICKET'), 0, ',', '.') . ' ,-')
                ->modalSubmitActionLabel('Lanjutkan Ke Pembayaran')
                ->mutateFormDataUsing(function (array $data) {
                    $data['user_id'] = auth()->user()->id;

                    return $data;
                })
                ->successRedirectUrl(function (Model $record) {
                    try {
                        $paymentController = new PaymentController;
    
                        $user = auth()->user();
                        $paymentAmount = (int)env('TICKET') * (int)$record->qty;
                        $productDetails = 'Pembelian Ticket Masuk Waterboom DM Tirta Persada';
                        $email = $user->email;
                        $customerVaName = $user->name;
                        $itemName = 'Tiket Waterboom DM Tirta Persada';
                        $itemPrice = $paymentAmount;
                        $itemQty = $record->qty;

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
                            $record->update([
                                'orderId' => $data['orderId'],
                                'paymentUrl' => $data['paymentUrl'],
                                'reference' => $data['reference'],
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
    
                        return $record->paymentUrl;
                    } catch (\Throwable $th) {
                        Notification::make()
                            ->title('Gagal')
                            // ->body('Mohon maaf, sedang ada gangguan')
                            ->body($message)
                            ->danger()
                            ->send();
                    }
                }
            ),
        ];
    }
}
