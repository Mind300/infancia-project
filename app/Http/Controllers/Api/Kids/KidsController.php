<?php

namespace App\Http\Controllers\Api\Kids;

use App\Http\Controllers\Controller;

// Requests
use App\Http\Requests\Kids\CreateKid;
use App\Http\Requests\Kids\UpdateKid;
use App\Models\Classes;
// Models
use App\Models\Kids;
use App\Models\Parents;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laratrust\Models\Role;
use Laratrust\Models\Team;

class KidsController extends Controller
{
    // Variables
    private $nursery_id;
    public static $creatingKid = false;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id ?? auth()->user()->employee->nursery_id;
        $this->middleware(['role:Manage-Classes|nursery_Owner']);
        $this->middleware(['role:teacher|parent'], ['only' => ['index', 'show', 'birthdayKids']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kids = Kids::where('nursery_id', $this->nursery_id)->get();
        $kidsWithMedia = $kids->map(function ($kid) {
            $kid->getFirstMedia('Kids');
            return [
                'kid' => $kid,
            ];
        });

        return contentResponse($kidsWithMedia, fetchAll('Kids'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateKid $request)
    {
        DB::beginTransaction();

        try {
            // Set the context flag
            self::$creatingKid = true;

            // Add Nursery Id In Request
            $requestValidated = $request->validated();
            $requestValidated['nursery_id'] = $this->nursery_id;

            // Create Kids & (Parents - User)
            $user = User::create($requestValidated);

            $requestValidated['user_id'] = $user->id;
            $parents = Parents::create($requestValidated);

            $requestValidated['parent_id'] = $parents->id;
            $kid = Kids::create($requestValidated);

            if ($request->has('media')) {
                $kid->addMediaFromRequest('media')->toMediaCollection('Kids');
            }

            // Increment Kid Count
            $class = Classes::find($request->class_id);
            $class->increment('kids_count');

            // Assign Role As a Parent
            $team = Team::where('name', auth()->user()->name . 'Team')->first();
            $role = Role::where('name', 'parent')->first();

            $user->addRole($role, $team->id);
            $user->syncRoles([$role], $team->id);

            DB::commit();

            return messageResponse('Created Kid Successfully');
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
        $kid = Kids::with('parent.user')->find($id);
        $kid->getFirstMedia('Kids');
        return contentResponse($kid, fetchOne($kid->kid_name));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kid = Kids::with('parent.user')->find($id);
        $kid->getFirstMedia('Kids');
        return contentResponse($kid, fetchOne($kid->kid_name));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKid $request, string $id)
    {
        DB::beginTransaction();

        try {
            $kid = Kids::find($id);
            $parent = Parents::find($kid->parent_id);
            $user = User::find($parent->user_id);

            $kid->update($request->validated());
            $kid->addMediaFromRequest('media')->toMediaCollection('Kids');
            $parent->update($request->validated());
            $user->update($request->validated());

            DB::commit();

            return messageResponse('Updated Kid Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kids $kid)
    {
        DB::beginTransaction();
        try {
            $class = Classes::findOrFail($kid->class_id);
            $class->decrement('kids_count');

            $kid->forceDelete();
            DB::commit();
            return messageResponse('Deleted Kid Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function birthdayKids($accessMonth)
    {
        if ($accessMonth == 'thisMonth') {
            $month = Carbon::now()->month;
        } else {
            $month = Carbon::now()->addMonth()->month;
        }

        $today = Carbon::today()->startOfDay();
        $kidsBirth = Kids::whereMonth('birthdate', $month)->where('nursery_id', $this->nursery_id)->get();

        $kids = $kidsBirth->map(function ($kid) use ($today) {
            $birthdateKid =  Carbon::parse($kid->birthdate);
            $birthDayThisYear = $birthdateKid->copy()->year($today->year);

            if ($birthDayThisYear < $today) {
                $birthDayThisYear->addYear();
            }

            return [
                'id' => $kid->id,
                'kid_name' => $kid->kid_name,
                'class' => $kid->class->name,
                'birthdate' => $kid->birthdate,
                'age' => $birthdateKid->diffInYears($today),
                'countdown' => $birthDayThisYear->diffInDays($today),
            ];
        });

        return contentResponse($kids, fetchAll('Kids Birthday Upcomming'));
    }
}
