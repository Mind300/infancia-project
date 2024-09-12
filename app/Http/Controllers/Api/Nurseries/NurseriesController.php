<?php

namespace App\Http\Controllers\Api\Nurseries;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nurseries\NurseryStatusRequest;
use App\Http\Requests\Nursery\CreateNursery;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

use App\Models\Employee;
use App\Models\Nurseries;
use App\Models\User;
use Laratrust\Models\Role;
use Laratrust\Models\Team;

use App\Notifications\RegitserNotification;
use App\Notifications\ApprovedNotification;
use App\Notifications\paymentSuccessNotification;
use App\Notifications\RejectedNotification;

class NurseriesController extends Controller
{
    public static $creatingNursery = false;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->middleware('role:nursery_Owner')->only(['nurseryUsers', 'edit', 'update']);
        $this->middleware('role:superAdmin')->only(['nurserySetStatus', 'nurseryApproved', 'destroy', 'blocked']);
    }

    /**
     * Display a listing of nurseries filtered by status.
     *
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(string $status)
    {
        $nursries = Nurseries::where('status', $status)->get();
        return contentResponse($nursries, 'Fetches Nurseries Successfully');
    }

    /**
     * Display a list of employees associated with the current nursery.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function nurseryUsers()
    {
        return contentResponse(Employee::where('nursery_id', nursery_id())->with('user')->get(), 'Fetches Users Nurseries Successfully');
    }

    /**
     * Store a newly created nursery in the database.
     *
     * @param CreateNursery $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateNursery $request)
    {
        DB::beginTransaction();
        try {
            // Create a new nursery using validated request data
            $nursery = Nurseries::create($request->validated());

            // Handle media file if present
            if ($request->hasFile('media')) {
                $nursery->addMediaFromRequest('media')->toMediaCollection('Nurseries');
            }

            // Notify about the nursery registration
            $nursery->notify(new RegitserNotification());

            DB::commit();
            return messageResponse('Success, Nursery Created Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Display the specified nursery's details.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $nursery = Nurseries::find($id);
        $nursery->getFirstMedia('Nurseries');
        return contentResponse($nursery, 'content');
    }

    /**
     * Update the specified nursery's details.
     *
     * @param CreateNursery $request
     * @param Nurseries $nursery
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CreateNursery $request, Nurseries $nursery)
    {
        DB::beginTransaction();
        try {
            // Update the nursery with validated request data
            $nursery->update($request->validated());

            // Handle media file if present
            if ($request->hasFile('media')) {
                $nursery->addMediaFromRequest('media')->toMediaCollection('Nurseries');
            }

            DB::commit();
            return messageResponse('Success, Nursery Updated Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Set the status of the specified nursery and notify accordingly.
     *
     * @param NurseryStatusRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nurserySetStatus(NurseryStatusRequest $request)
    {
        DB::beginTransaction();
        try {
            // Find the nursery with a specific email (this is hard-coded and should be parameterized)
            $nursery = Nurseries::findOrFail($request->validated('nursery_id'));
            if ($request->validated('status') === 'accepted') {
                // Process approval if status is 'accepted'
                self::$creatingNursery = true;

                // Create a user associated with the nursery
                $user = User::create($nursery->toArray());
                $token = Password::createToken($user);
                $nursery->update(['user_id' => $user->id]);

                // Assign roles and teams to the user
                $role = Role::where('name', 'nursery_Owner')->first();
                $team = Team::create(['name' => $nursery->name . 'Team']);
                $user->addRole($role, $team);
                $user->syncRoles([$role], $team);
                $teacher = Role::create(['name' => 'teacher', 'team_id' => $team->id]);
                $user->syncRoles([$role], $team);

                DB::commit();
                $nursery->notify(new ApprovedNotification($token));
            } else {
                // Notify about rejection
                $nursery->notify(new RejectedNotification($nursery));
            }
            return messageResponse('Nursery Is ' . $request->validated('status'));
        } catch (\Throwable $error) {
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Handle actions required for approving a nursery.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function nurseryApproved(string $nursery_id)
    {
        try {
            self::$creatingNursery = true;

            // Find the nursery with a specific email (this is hard-coded and should be parameterized)
            $nursery = Nurseries::find($nursery_id);
            dd($nursery);

            // Create a user associated with the nursery
            $user = User::create($nursery->toArray());
            $token = Password::createToken($user);
            $nursery->update(['user_id' => $user->id]);

            // Assign roles and teams to the user
            $role = Role::where('name', 'nursery_Owner')->first();
            $team = Team::create(['name' => $nursery->name . 'Team']);
            $user->addRole($role, $team);
            $user->syncRoles([$role], $team);
            $teacher = Role::create(['name' => 'teacher', 'team_id' => $team->id]);
            $user->syncRoles([$role], $team);

            DB::commit();
            return $token;
        } catch (\Throwable $error) {
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Remove the specified nursery from the database.
     *
     * @param Nurseries $nursery
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Nurseries $nursery)
    {
        $nursery->forceDelete();
        return messageResponse('Nursery Deleted Successfully');
    }

    /**
     * Block or Unblock the specified nursery.
     *
     * @param Nurseries $nursery
     * @return \Illuminate\Http\JsonResponse
     */
    public function blocked(Nurseries $nursery)
    {
        $account = $nursery->user()->withTrashed()->first();
        $account->deleted_at ? $account->restore() : $account->delete();
        return messageResponse(($account->deleted_at ? 'Blocked ' : 'Restore ') . $nursery->name . ' Successfully');
    }
}
