<?php

namespace App\Http\Controllers\Api\Nurseries;

use App\Http\Controllers\Controller;
use App\Http\Requests\PolicyRequest;
use App\Models\Policy;

class PolicyController extends Controller
{
    // Variables
    private $nursery_id;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id;
    }

    /**
     * Display a listing of the policies.
     */
    public function index()
    {
        $policies = Policy::where('nursery_id', $this->nursery_id)->get();
        return contentResponse($policies, fetchAll('Nurseries Policeies'));
    }

    /**
     * Store a newly created policy in storage.
     */
    public function store(PolicyRequest $request)
    {
        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = $this->nursery_id;

        $policy = Policy::create($requestValidated);
        return contentResponse($policy, 'Policy created successfully');
    }

    /**
     * Display the specified policy.
     */
    public function show(PolicyRequest $policy)
    {
        return contentResponse($policy, fetchOne('Nursery Policy'));
    }

    /**
     * Update the specified policy in storage.
     */
    public function update(PolicyRequest $request, $id)
    {
        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = $this->nursery_id;
        
        $policy = Policy::findOrFail($id)->update($requestValidated);
        return contentResponse($policy, 'Policy updated successfully');
    }

    /**
     * Remove the specified policy from storage.
     */
    public function destroy(Policy $policy)
    {
        $policy->forceDelete();
        return messageResponse('Policy deleted successfully');
    }
}
