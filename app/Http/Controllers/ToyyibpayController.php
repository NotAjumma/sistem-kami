<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;


use Illuminate\Http\Request;

class ToyyibpayController extends Controller
{
    public function createBill()
    {

        $bill_data = array(
            'userSecretKey' => config('toyyibpay.api_key'),
            'categoryCode' => config('toyyibpay.category_code'),
            'billName' => 'Car Rental WXX123',
            'billDescription' => 'Car Rental WXX123 On Sunday',
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => 1000, //in cent
            'billReturnUrl' => route('toyyibpay.status'),
            'billCallbackUrl' => route('toyyibpay.callback'),
            'billExternalReferenceNo' => 'Bill-0001',
            'billTo' => 'Naim Daniel',
            'billEmail' => 'naim@gmail.com',
            'billPhone' => '0194342411',
            'billSplitPayment' => 0,
            'billSplitPaymentArgs' => '',
            'billPaymentChannel' => '0',
            'billContentEmail' => 'Thank you for purchasing our product!',
            'billChargeToCustomer' => 0,
            // 'billExpiryDate' => '17-12-2020 17:00:00',
            // 'billExpiryDays' => 3
        );

        $url = 'https://dev.toyyibpay.com/index.php/api/createBill';
        $response = Http::asForm()->post($url, $bill_data);
        \Log::info($response);
        $billCode = $response[0]['BillCode'];

        // \Log::info($response);
        return redirect('https://dev.toyyibpay.com/' . $billCode);
    }

    public function paymentStatus()
    {
        $response = request()->all(['status_id', 'billcode', 'order_id']);
        return $response;
    }

    public function callback()
    {
        $response = request()->all();
        \Log::info($response);
    }
}
