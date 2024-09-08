<?php

namespace App\Http\Controllers\Api\Nurseries;

use App\Http\Controllers\Api\Payments\PaymentController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Nurseries\NurseryApprovedRequest;
use App\Http\Requests\Nurseries\NurseryStatusRequest;
use App\Http\Requests\Nursery\CreateNursery;
use App\Models\Employee;
use App\Models\Nurseries;
use App\Models\User;
use App\Notifications\ApproveNotification;
use App\Notifications\paymentSuccessNotification;
use App\Notifications\RegitserNotification;
use App\Notifications\RejectedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Laratrust\Models\Role;
use Laratrust\Models\Team;

class NurseriesController extends Controller
{
    // Variables
    private $nursery_id;
    public static $creatingNursery = false;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id ?? auth()->user()->employee->nursery_id ?? null;
        $this->middleware(['role:nursery_Owner|superAdmin|permission:Nursery-Profile']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $status)
    {
        $nursries = Nurseries::where('status', $status)->get();
        return contentResponse($nursries, 'Fetches Nurseries Successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNursery $request)
    {
        DB::beginTransaction();
        try {
            $nursery = Nurseries::create($request->validated());
            if ($request->has('media')) {
                $nursery->addMediaFromRequest('media')->toMediaCollection('Nurseries');
            }
            $nursery->notify(new RegitserNotification());

            DB::commit();
            return messageResponse('Success, Nursery Created Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $nursery = Nurseries::find($id);
        $nursery->getFirstMedia('Nurseries');
        return contentResponse($nursery, 'content');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateNursery $request, Nurseries $nursery)
    {
        DB::beginTransaction();
        try {
            $nursery->update($request->validated());
            if ($request->has('media')) {
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
     * Remove the specified resource from storage.
     */
    public function destroy(Nurseries $nursery)
    {
        $nursery->forceDelete();
        return messageResponse('Nursery Deleted Successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function nurserySetStatus(NurseryStatusRequest $request)
    {
        try {
            $nursery = Nurseries::findOrFail($request->validated('nursery_id'));
            if ($request->validated('status') === 'accepted') {
                $nursery->notify(new ApproveNotification($nursery));
            } else {
                $nursery->notify(new RejectedNotification($nursery));
            }
            return messageResponse('Nursery Is ' . $request->validated('status'));
        } catch (\Throwable $error) {
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function nurseryApproved(NurseryApprovedRequest $request)
    {
        $paymob = new PaymentController();
        $paymobCallback = $paymob->callback($request->validated('transaction_id'));
        if ($paymobCallback['success'] === true) {
            self::$creatingNursery = true;
            $nursery = Nurseries::firstWhere('email', 'khaledmoussa202@gmail.com');
            $user = User::create($nursery->toArray());
            $token = Password::createToken($user);
            $nursery->update(['user_id' => $user->id]);

            $role = Role::where('name', 'nursery_Owner')->first();
            $team = Team::create(['name' => $nursery->name . 'Team']);

            $user->addRole($role, $team);
            $user->syncRoles([$role], $team);

            $teacher = Role::create(['name' => 'teacher', 'team_id' => $team->id]);
            $user->syncRoles([$role], $team);

            $nursery->notify(new paymentSuccessNotification($token));

            DB::commit();
            return messageResponse('Nursery Approved Successfully');
        } else {
            return messageResponse('Failed, Error Occured During Payment, Try Again..!', 403);
        }
    }


    // Display a listing of the resource.
    public function nurseryUsers()
    {
        return contentResponse(Employee::where('nursery_id', $this->nursery_id)->with('user')->get(), 'Fetches Users Nurseries Successfully');
    }
}
