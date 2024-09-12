<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;

class PaymentHistoryController extends Controller
{
    public function paymentHistoryNurseries()
    {
        $paymentHistroy = PaymentHistory::get();
        return contentResponse($paymentHistroy);
    }
}
