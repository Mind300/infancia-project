<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUser;
use App\Http\Requests\Users\UpdateUser;
// Request
use Illuminate\Http\Request;
// Models
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laratrust\Models\Permission;
use Laratrust\Models\Role;
use Laratrust\Models\Team;

class UsersController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['role:AdminRole']);
        // $this->middleware(['role:Employee'], ['only' => ['store', 'update']]);

        // $this->middleware(['permission:users-create'], ['only' => ['store']]);
        // $this->middleware(['permission:users-read'], ['only' => ['index', 'show']]);
        // $this->middleware(['permission:users-update'], ['only' => ['edit', 'update']]);
        // $this->middleware(['permission:users-delete'], ['only' => ['destroy']]);
    }

    // Display a listing of the resource.
    public function index()
    {
        return contentResponse(User::get(), 'Fetches Users Successfully');
    }

    // Store a newly created resource in storage.
    public function store(CreateUser $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create($request->validated());
            // $user->addMediaFromRequest('image')->toMediaCollection('profiles');

            $team = Team::where('name', auth()->user()->nursery->name . 'Team')->first();
            $role = Role::where('name', $request->safe()->only('role'))->where('team_id', $team->id)->first();

            $user->addRole($role, $team);
            $user->syncRoles([$role], $team);

            // $showusers = Permission::where('name', 'show_users')->first();
            // $user->givePermission($showusers, $team);
            DB::commit();

            return messageResponse('Success, User Created Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    // Display the specified resource.
    public function show(User $user)
    {
        $user->getFirstMedia('profiles');
        return contentResponse($user, 'View User Successfully');
    }

    // Show the form for editing the specified resource.
    public function edit(User $user)
    {
        $test = $user->getFirstMediaUrl('profiles');
        return contentResponse($test, 'Edit User Successfully');
    }

    // Update the specified resource in storage.
    public function update(UpdateUser $request, string $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->update($request->validated());
            $user->assignRole($request->validated('role'));
            $user->addMediaFromRequest('image')->toMediaCollection('avatar');
            DB::commit();
            return messageResponse('Success, User Updated Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    // Remove the specified resource from storage.
    public function destroy(User $user)
    {
        try {
            $user->forceDelete();
            return messageResponse('Success, User Deleted Successfully');
        } catch (\Throwable $th) {
            return messageResponse('Failed, An Error Occured When Deleting User..!!', 404);
        }
    }
}
