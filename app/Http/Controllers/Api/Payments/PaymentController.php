<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use App\Models\Nurseries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(public $nursery = null)
    {
        $this->nursery = $nursery;
    }

    public function paymentCreateSubscription()
    {
        $tokenResponse = $this->paymentConfig();
        $token = $tokenResponse['token'];
        $order = $this->paymentCreateOrder($token);
        $paymentToken = $this->paymentKeys($order, $token);
        return 'https://portal.weaccept.co/api/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentToken;
    }

    public function paymentConfig()
    {
        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => env('PAYMOB_API_KEY')
        ]);

        return $response->json();
    }

    public function paymentCreateOrder($token)
    {
        $items = [
            [
                "name" => $this->nursery->name,
                "amount_cents" => "5000",  // 5000.00 EGP
                "description" => "Subscribtion",
                "quantity" => $this->nursery->children_number,
            ],
        ];

        $totalAmountCents = array_sum(array_map(function ($item) {
            return $item['amount_cents'] * $item['quantity'];
        }, $items));

        $data = [
            "auth_token" => $token,
            "delivery_needed" => "false",
            "amount_cents" => $totalAmountCents,
            "currency" => "EGP",
            "items" => $items,
        ];

        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', $data);
        return $response->json();
    }

    public function paymentKeys($order, $token)
    {
        $billingData = [
            "first_name" => $this->nursery->name, //Required
            "last_name" => "Nicolas", //Optional
            "email" => $this->nursery->email, //Required
            "city" => $this->nursery->city,
            "state" => "Egypt", //Required
            "country" => "Egypt", //Required
            "phone_number" => $this->nursery->phone, //Required
            "apartment" => "null", //Required
            "floor" => "null", //Required
            "postal_code" => "null", //Optional
            'street' => 'null', //Required
            "building" => "null", //Required
            "shipping_method" => "null", //Optional
        ];

        $data = [
            "auth_token" => $token,
            "amount_cents" => $order['amount_cents'],
            "expiration" => 3600,
            "order_id" => $order['id'],
            "billing_data" => $billingData,
            'email' => 'khaledmoussa202@gmail.com',
            "currency" => "EGP",
            "integration_id" => env('PAYMOB_INTEGRATION_ID')
        ];

        $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', $data);
        return $response->json('token');
    }

    public function callback($transaction_id)
    {
        $tokenResponse = $this->paymentConfig();
        $token = $tokenResponse['token'];
        $response = Http::withHeaders(['Authorization' => "Bearer $token",])->get("https://accept.paymob.com/api/acceptance/transactions/{$transaction_id}");

        if ($response->successful()) {
            return $response->json();
        } else {
            return $response->json();
        }
    }
}
