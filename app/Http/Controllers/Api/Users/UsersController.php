<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUser;
use App\Http\Requests\Users\UpdateUser;
use App\Models\Employee;
use App\Models\ManageClass;
// Models
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laratrust\Models\Role;
use Laratrust\Models\Team;

class UsersController extends Controller
{
    private $nursery_id;
    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->middleware(['role:nursery_Owner|superAdmin']);
    }

    // Display a listing of the resource.
    public function index()
    {
        return contentResponse(Employee::where('nursery_id', nursery_id())->with('user')->get(), 'Fetches Users Successfully');
    }

    // Store a newly created resource in storage.
    public function store(CreateUser $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create($request->validated());
            $team = Team::where('name', auth()->user()->nursery->name . 'Team' ?? auth()->user()->name . 'Team')->first();
            $role = Role::where('name', $request->safe()->only('role'))->where('team_id', $team->id)->first();

            $user->addRole($role, $team);
            $user->syncRoles([$role], $team);
            $data = ['type' => $role->name, 'user_id' => $user->id, 'nursery_id' => nursery_id(),];

            if ($role->name == 'teacher' && $request->has('classes')) {
                foreach ($request->validated('classes') as $class) {
                    ManageClass::create(['user_id' => $user->id, 'class_id' => $class['class_id'], 'nursery_id' => nursery_id()]);
                }
            }
            if (nursery_id()) {
                Employee::create($data);
            }
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
