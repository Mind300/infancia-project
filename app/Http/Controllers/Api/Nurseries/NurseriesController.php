<?php

namespace App\Http\Controllers\Api\Nurseries;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nursery\CreateNursery;
use App\Models\Nurseries;
use App\Models\Nursery;
use App\Models\User;
use Illuminate\Http\Request;
use Laratrust\Models\Permission;
use Laratrust\Models\Role;
use Laratrust\Models\Team;

class NurseriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nursries = Nurseries::get();
        return contentResponse($nursries, 'Fetches Nurseries Successfully');
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create(CreateNursery $request)
    // {
    //     $nursries = Nurseries::get();
    //     return contentResponse($nursries, 'Fetches Nurseries Successfully');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNursery $request)
    {
        try {
            $user = User::create($request->safe()->only(['name', 'email', 'phone', 'password']));
            $nurseryData = $request->safe()->except(['email', 'phone', 'password']);

            $nurseryData['user_id'] = $user->id;
            $nursery = Nurseries::create($nurseryData);

            $team = Team::create(['name' => $user->name . 'Team']);
            $role = Role::where('name', 'nursery')->first();

            $user->addRole($role, $team);
            $user->syncRoles([$role], $team);

            return messageResponse('Success, Nursery Created Successfully');
        } catch (\Throwable $th) {
            dd($th);
        }
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
