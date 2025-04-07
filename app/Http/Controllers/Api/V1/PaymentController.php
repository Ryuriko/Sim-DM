<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;
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

        $ticket = Ticket::where('reference', $data['reference'])->where('orderId', $data['merchantOrderId'])->first();
        if($data['resultCode'] == 00) {
            $ticket->update([
                'status' => 'paid'
            ]);

            $qrCodePath = 'qrcodes/' . $ticket['id'] . '.png';
            $fullPath = storage_path('app/public/' . $qrCodePath);

            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            QrCode::format('png')
                ->size(250)
                ->generate($ticket['reference']. ' ' .  $ticket['orderId'], $fullPath);

            $ticket->update(['qrcode' => $qrCodePath]);
        } else if($data['resultCode'] == 02) {
            $ticket->update([
                'status' => 'unpaid'
            ]);
        }

        return redirect('/user/tickets');
    }
}
