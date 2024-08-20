<?php

namespace App\Http\Controllers\Api\Parent;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    // Variables
    private $user_id;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->user_id = auth()->user()->id;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parents = Parents::with('kids')->where('user_id', $this->user_id)->get();
        $parent = $parents->flatMap(function ($parent) {
            return $parent->kids->map(function ($kid) {
                return [
                    'nursery_id' => $kid->nursery->id,
                    'nursery_name' => $kid->nursery->name,
                    'kid_id' => $kid->id,
                    'kid_name' => $kid->kid_name,
                    'class_id' => $kid->class_id,
                    'media' => $kid->getFirstMedia('Kids'),
                ];
            });
        });

        return contentResponse($parent, fetchAll('Parent Kids'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
