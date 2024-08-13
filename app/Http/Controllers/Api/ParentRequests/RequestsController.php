<?php

namespace App\Http\Controllers\Api\ParentRequests;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParentRequest\ParentRequests;
use App\Models\ParentRequest;

class RequestsController extends Controller
{
    // Variables
    private $nursery_id;
    private $user_id;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id;
        $this->user_id = auth()->user()->id;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = ParentRequest::with('user')->where('nursery_id', $this->nursery_id)->get();
        return contentResponse($requests);
    }

    /**
     * Display a listing of the resource.
     */
    public function indexParent()
    {
        $requests = ParentRequest::with('user')->where('user_id', $this->user_id)->get();
        return contentResponse($requests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ParentRequests $request)
    {
        $dataValidated = $request->validated();
        $dataValidated['user_id'] = $this->user_id;
        $dataValidated['nursery_id'] = $this->nursery_id;

        ParentRequest::create($dataValidated);
        return messageResponse('Created Request Sucessfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $parentRequest = ParentRequest::find($id);
        $parentRequest->update(['seen' => 1]);
        
        return contentResponse($parentRequest, fetchOne("Parent Request"));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $parentRequest = ParentRequest::find($id);
        return contentResponse($parentRequest, fetchOne("Parent Request"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ParentRequests $request, string $id)
    {
        $dataValidated = $request->validated();
        $dataValidated['user_id'] = $this->user_id;

        $parentRequest = ParentRequest::find($id)->update($dataValidated);
        return messageResponse('Updated Request Sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ParentRequest::find($id)->forceDelete();
        return messageResponse('Deleted Request Sucessfully');
    }
}