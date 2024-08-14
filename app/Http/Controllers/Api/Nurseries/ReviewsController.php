<?php

namespace App\Http\Controllers\Api\Nurseries;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reviews\ReviewsRequest;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewsController extends Controller
{
    // Variables
    private $nursery_id;

    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Reviews::where('nursery_id', $this->nursery_id)->get();
        return contentResponse($reviews, fetchAll('Reviews'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewsRequest $request)
    {
        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = $this->nursery_id;
        $reviews = Reviews::create($requestValidated);
        return messageResponse('Created Review Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reviews $review)
    {
        return contentResponse($review, fetchAll('Review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewsRequest $request, Reviews $review)
    {
        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = $this->nursery_id;
        try {
            $reviews = $review->update($requestValidated);
            return messageResponse('Updated Review Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reviews $review)
    {
        $review->forceDelete();
        return messageResponse('Review Deleted Successfully');
    }
}
