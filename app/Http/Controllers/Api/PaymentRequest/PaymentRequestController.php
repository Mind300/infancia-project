<?php

namespace App\Http\Controllers\Api\PaymentRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest\PaymentReq;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use Carbon\Carbon;
use DateTime;

class PaymentRequestController extends Controller
{
    // Variables
    private $nursery_id;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id ?? auth()->user()->employee->nursery_id;
        $this->middleware(['role:nursery_Owner|parent|permission:Payment-Request']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentRequest = PaymentRequest::with('kids.class')->where('nursery_id', $this->nursery_id)->get();
        return contentResponse($paymentRequest, fetchAll('All Payment Request'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentReq $request)
    {
        $requestValidated = $request->validated();
        $requestValidated['paid_at'] = Carbon::today();
        $requestValidated['nursery_id'] = $this->nursery_id;

        // Process meal amounts
        foreach ($requestValidated['kids'] as $meal) {
            $paymentRequest = PaymentRequest::create([
                'service' => $requestValidated['service'],
                'amount' => $requestValidated['amount'],
                'kid_id' => $meal['kid_id'],
                'nursery_id' => $requestValidated['nursery_id'],
            ]);
        }
        return messageResponse('Create Payment Request Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paymentRequest = PaymentRequest::findOrFail($id);
        return contentResponse($paymentRequest, fetchAll('All Payment Request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function makrPaied(string $id)
    {
        $paymentRequest = PaymentRequest::findOrFail($id);
        if ($paymentRequest->is_paid) {
            return messageResponse('This payment has already been marked as paid');
        }
        $paymentRequest->update(['is_paid' => true, 'paid_at' => Carbon::today()]);

        return contentResponse($paymentRequest, fetchOne('Paied Successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
