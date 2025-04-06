<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

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

    public function callback()
    {
        $apiKey = env('DUITKU_API_KEY');
        $merchantCode = isset($_POST['merchantCode']) ? $_POST['merchantCode'] : null; 
        $amount = isset($_POST['amount']) ? $_POST['amount'] : null; 
        $merchantOrderId = isset($_POST['merchantOrderId']) ? $_POST['merchantOrderId'] : null; 
        $productDetail = isset($_POST['productDetail']) ? $_POST['productDetail'] : null; 
        $additionalParam = isset($_POST['additionalParam']) ? $_POST['additionalParam'] : null; 
        $paymentCode = isset($_POST['paymentCode']) ? $_POST['paymentCode'] : null; 
        $resultCode = isset($_POST['resultCode']) ? $_POST['resultCode'] : null; 
        $merchantUserId = isset($_POST['merchantUserId']) ? $_POST['merchantUserId'] : null; 
        $reference = isset($_POST['reference']) ? $_POST['reference'] : null; 
        $signature = isset($_POST['signature']) ? $_POST['signature'] : null; 
        $publisherOrderId = isset($_POST['publisherOrderId']) ? $_POST['publisherOrderId'] : null; 
        $spUserHash = isset($_POST['spUserHash']) ? $_POST['spUserHash'] : null; 
        $settlementDate = isset($_POST['settlementDate']) ? $_POST['settlementDate'] : null; 
        $issuerCode = isset($_POST['issuerCode']) ? $_POST['issuerCode'] : null; 

        //log callback untuk debug 
        // file_put_contents('callback.txt', "* Callback *\r\n", FILE_APPEND | LOCK_EX);

        if(!empty($merchantCode) && !empty($amount) && !empty($merchantOrderId) && !empty($signature))
        {
            $params = $merchantCode . $amount . $merchantOrderId . $apiKey;
            $calcSignature = md5($params);

            if($signature == $calcSignature)
            {
                //Callback tervalidasi
                //Silahkan rubah status transaksi anda disini
                // file_put_contents('callback.txt', "* Berhasil *\r\n\r\n", FILE_APPEND | LOCK_EX);

            }
            else
            {
                // file_put_contents('callback.txt', "* Bad Signature *\r\n\r\n", FILE_APPEND | LOCK_EX);
                throw new Exception('Bad Signature');
            }
        }
        else
        {
            // file_put_contents('callback.txt', "* Bad Parameter *\r\n\r\n", FILE_APPEND | LOCK_EX);
            throw new Exception('Bad Parameter');
        }
    }
}
