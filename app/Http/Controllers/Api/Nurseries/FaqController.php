<?php

namespace App\Http\Controllers\Api\Nurseries;

use App\Http\Controllers\Controller;
use App\Http\Requests\FAQ\FaqRequest;
use App\Models\Faq;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    private $nursery_id;

    public function __construct()
    {
        $this->middleware(['role:nursery_Owner|teacher|parent|permission:Faq']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faq = Faq::where('nursery_id', nursery_id())->get();
        return contentResponse($faq, fetchAll('FAQ'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqRequest $request)
    {
        DB::beginTransaction();
        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = nursery_id();
        try {
            $faq = Faq::create($requestValidated);
            DB::commit();
            return messageResponse('Created FAQ Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(Faq $faq)
    {
        return contentResponse($faq, fetchOne('FAQ'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        return contentResponse($faq, fetchOne('FAQ'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        DB::beginTransaction();
        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = nursery_id();
        try {
            $faq->update($requestValidated);
            DB::commit();
            return messageResponse('Updated FAQ Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->forceDelete();
        return messageResponse('Deleted FAQ Successfully');
    }
}
