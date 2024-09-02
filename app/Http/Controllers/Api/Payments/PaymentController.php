<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use App\Models\Nurseries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function __construct(public $nursery)
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

    public function callback(Request $request)
    {
        $data = $request->all();
        ksort($data);

        $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];

        $connectedString = '';
        foreach ($array as $key) {
            if (isset($data[$key])) {
                $connectedString .= $data[$key];
            }
        }

        $secret = env('PAYMOB_HMAC');
        $hashed = hash_hmac('SHA512', $connectedString, $secret);

        if ($hashed === $hmac) {
            echo "secure";
            exit;
        }

        echo 'not secure';
        exit;
    }
}
