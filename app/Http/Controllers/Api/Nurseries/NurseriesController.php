<?php

namespace App\Http\Controllers\Api\Nurseries;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nurseries\ApproveNursery;
use App\Http\Requests\Nursery\CreateNursery;
use App\Http\Requests\Nursery\GalleryRequest;
use App\Models\Nurseries;
use App\Models\User;
use App\Notifications\ApproveNotification;
use App\Notifications\RegitserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Laratrust\Models\Role;
use Laratrust\Models\Team;
use Illuminate\Support\Str;

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
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id ?? null;
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
    /**
     * Remove the specified resource from storage.
     */
    public function nurseryAprrove(ApproveNursery $request)
    {
        DB::beginTransaction();
        try {
            // Set the context flag
            self::$creatingNursery = true;

            $nursery = Nurseries::findOrFail($request->validated('nursery_id'));

            $user = User::create($nursery->toArray());
            $token = Password::createToken($user);

            $nursery->update(['status' => $request->validated('status'), 'user_id' => $user->id]);

            $team = Team::create(['name' => $user->name . 'Team']);
            $role = Role::where('name', 'nursery_Owner')->first();
            $user->addRole($role, $team);
            $user->syncRoles([$role], $team);

            $user->notify(new ApproveNotification($user, $token, $request->validated('status')));
            DB::commit();
            return messageResponse('Created Nursery Approve Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }
}
