<?php

namespace App\Http\Controllers\Api\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\CreateRole;
use App\Models\User;
use Illuminate\Http\Request;

use Laratrust\Models\Permission;
use Laratrust\Models\Role;
use Laratrust\Models\Team;

class RoleController extends Controller
{

    public function __construct()
    {
        // $this->middleware(['role:AdminRole']);
        // $this->middleware(['role:Employee'], ['only' => ['store', 'update']]);

        // $this->middleware(['permission:roles-create'], ['only' => ['store']]);
        // $this->middleware(['permission:roles-read'], ['only' => ['index', 'show']]);
        // $this->middleware(['permission:roles-update'], ['only' => ['edit', 'update']]);
        // $this->middleware(['permission:roles-delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return contentResponse($roles, 'Success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRole $request)
    {
        // Get the team associated with the current user
        $team = Team::where('name', auth()->user()->name . 'Team')->first();
        $roleData = $request->safe()->except('permissions');
        $roleData['team_id'] = $team->id;

        $role = Role::create($roleData);
        $permissions = $request->safe()->only('permissions')['permissions'];

        $permissionObjects = Permission::whereIn('name', array_column($permissions, 'name'))->get();
        $role->permissions()->sync($permissionObjects->pluck('id'));

        return messageResponse('Created Role Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::with('permissions')->find($id);
        return contentResponse($role, 'Success');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
        return contentResponse($role, 'Success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRole $request, string $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->safe()->except('permissions'));

        if ($request->has('permissions')) {
            $permissions = $request->safe()->only('permissions')['permissions'];
            $permissionObjects = Permission::whereIn('name', array_column($permissions, 'name'))->get();
            $role->permissions()->sync($permissionObjects->pluck('id'));
        }

        return messageResponse('Updated Role Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach();
        $role->delete();
        return messageResponse('Deleted Role Successfully.');
    }
}
