<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ToyyibPayService
{
    protected $baseUrl;
    protected $apiKey;
    protected $categoryCode;

    public function __construct()
    {
        $this->baseUrl = config('toyyibpay.sandbox') ? 'https://dev.toyyibpay.com' : 'https://toyyibpay.com';
        $this->apiKey = config('toyyibpay.api_key');
        $this->categoryCode = config('toyyibpay.category_code');
    }

    public function createBill(array $data)
    {
        $payload = [
            'userSecretKey' => $this->apiKey,
            'categoryCode' => $this->categoryCode,
            'billName' => $data['name'],
            'billDescription' => $data['description'],
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => $data['amount'] * 100, // sen
            'billReturnUrl' => config('toyyibpay.return_url'),
            'billCallbackUrl' => config('toyyibpay.return_url'),
            'billExternalReferenceNo' => $data['ref_no'],
            'billTo' => $data['to'],
            'billEmail' => $data['email'],
            'billPhone' => $data['phone'],
        ];

        $response = Http::asForm()->post($this->baseUrl . '/index.php/api/createBill', $payload);
            \Log::info($response);

        return $response->json();
    }
}
