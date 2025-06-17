<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;
use App\Models\GymSubscription;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{
    public function pay(
        $paymentAmount,
        $productDetails,
        $email,
        $customerVaName,
        $itemName,
        $itemPrice,
        $itemQty
    )
    {
        $endpoint = 'https://api-sandbox.duitku.com/api/merchant/createInvoice';
        $merchantCode = env('DUITKU_MERCHANT_CODE');
        $merchantKey = env('DUITKU_API_KEY');

        $timestamp = round(microtime(true) * 1000);
        $merchantOrderId = time() . '';
        $callbackUrl = 'http://sempro.test:8080/payment/callback';
        $returnUrl = 'http://sempro.test:8080/payment/callback';
        $expiryPeriod = 10;
        $signature = hash('sha256', $merchantCode.$timestamp.$merchantKey);
        $item = array(
            'name' =>  $itemName,
            'price' =>  (int)$itemPrice,
            'quantity' =>  (int)$itemQty,
        );
        $itemDetails = array(
            $item
        );

        $customerDetail = array(
            'firstName' =>  $customerVaName,
            'email' =>  $email,
        );

        $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'x-duitku-signature' => $signature,
                'x-duitku-timestamp' => $timestamp,
                'x-duitku-merchantcode' => $merchantCode,
            ])->post($endpoint, [
                'paymentAmount' => (int)$paymentAmount,
                'merchantOrderId' => (string)$merchantOrderId,
                'productDetails' => (string)$productDetails,
                'email' => (string)$email,
                'customerVaName' => (string)$customerVaName,
                'itemDetails' => $itemDetails,
                'customerDetail' => $customerDetail,
                'expiryPeriod' => (int)$expiryPeriod,
                'callbackUrl' => (string)$callbackUrl,
                'returnUrl' => (string)$returnUrl,
            ]);

            
        $statusCode = $response->getStatusCode();
        $data = $response->json();
        if ($statusCode == 200) {
            $data = array_merge($data, ['orderId' => $merchantOrderId]);

            return [
                'status' => 'success',
                'message' => 'OK',
                'data' => $data
            ];
        } else {
            return [
                'status' => 'failed',
                'message' => $data
            ];
        }

    }

    public function callback(Request $request)
    {
        $data = $request->all();

        $transaksi = Transaksi::where('reference', $data['reference'])->where('orderId', $data['merchantOrderId'])->first();
        if($data['resultCode'] == 00) {
            $transaksi->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);

            $qrCodePath = 'qrcodes/' . $transaksi['id'] . '.png';
            $fullPath = storage_path('app/public/' . $qrCodePath);

            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            QrCode::format('png')
                ->size(250)
                ->generate($transaksi->tipe . ' ' . $transaksi['reference'] . ' ' .  $transaksi['orderId'], $fullPath);

            $transaksi->update(['qrcode' => $qrCodePath]);

        }
        // else if($data['resultCode'] == 02) {
        //     dd($transaksi);
        //     $transaksi->update([
        //         'status' => 'unpaid'
        //     ]);
        // }

        if($transaksi != null) {
            if($transaksi->tipe == 'ticket'){
                return redirect('/tickets');
            } else if($transaksi->tipe == 'ticket-ots'){
                return redirect('/ticket-waterbooms');
            } else if($transaksi->tipe == 'reservasi'){
                return redirect('/reservasi-pelanggans');
            } else if($transaksi->tipe == 'reservasi-ots'){
                return redirect('/reservasis');
            } else if($transaksi->tipe == 'gym'){
                if($data['resultCode'] == 00) {
                    $gym = GymSubscription::where('transaksi_id', $transaksi->id)->first();
                    $gym->update(['status' => 'aktif']);
                }
    
                return redirect('/gym-pelanggans');
            } else if($transaksi->tipe == 'gym-ots'){
                return redirect('/gym-subscriptions');
            } else if($transaksi->tipe == 'parkir'){
                return redirect('/parkir-pelanggans');
            } else if($transaksi->tipe == 'parkir-ots'){
                return redirect('/parkirs');
            } 
        } else {
            return redirect('/');
        }
    }
}
