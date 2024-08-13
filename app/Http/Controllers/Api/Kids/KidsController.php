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
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kids = Kids::where('nursery_id', $this->nursery_id)->get();
        return contentResponse($kids, fetchAll('Kids'));
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
            Kids::create($requestValidated);

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
    public function show(Kids $kid)
    {
        $kid->parent->user;
        return contentResponse($kid, fetchOne($kid->kid_name));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kids $kid)
    {
        $kid->parent->user;
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
        try {
            $kid->forceDelete();
            return messageResponse('Deleted Kid Successfully');
        } catch (\Throwable $error) {
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
        $kidsBirth = Kids::whereMonth('birthdate', $month)->get();

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
