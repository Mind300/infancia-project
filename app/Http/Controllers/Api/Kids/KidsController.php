<?php

namespace App\Http\Controllers\Api\Kids;

use App\Http\Controllers\Controller;
// Requests
use App\Http\Requests\Kids\CreateKid;
use App\Http\Requests\Kids\UpdateKid;
// Models
use App\Models\Classes;
use App\Models\Kids;
use App\Models\Parents;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laratrust\Models\Role;
use Laratrust\Models\Team;

class KidsController extends Controller
{
    // Flag to track if a kid is being created
    public static $creatingKid = false;

    /**
     * Construct a new instance of the controller.
     * Apply middleware for authentication and role-based access.
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'role:nursery_Owner|teacher|permission:Manage-Classes']);
    }

    /**
     * Display a listing of kids for the current nursery.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $kids = Kids::where('nursery_id', nursery_id())->get();
        $kidsWithMedia = $kids->map(function ($kid) {
            $kid->getFirstMedia('Kids');
            return [
                'kid' => $kid,
            ];
        });

        return contentResponse($kidsWithMedia, fetchAll('Kids'));
    }

    /**
     * Store a newly created kid in the database.
     * 
     * @param CreateKid $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateKid $request)
    {
        DB::beginTransaction();

        try {
            // Set the context flag for creating a kid
            self::$creatingKid = true;

            // Validate and augment the request data
            $requestValidated = $request->validated();
            $requestValidated['nursery_id'] = nursery_id();

            $user = User::firstWhere('email', $requestValidated['email']);
            if (!$user) {
                // Create new user and parent if not found
                $user = User::create($requestValidated);
                $requestValidated['user_id'] = $user->id;
                $parent = Parents::create($requestValidated);
            } else {
                // Retrieve existing parent
                $parent = Parents::firstWhere('user_id', $user->id);
            }

            // Create the kid
            $requestValidated['parent_id'] = $parent->id;
            $kid = Kids::create($requestValidated);

            // Handle media upload if provided
            if ($request->hasFile('media')) {
                $kid->addMediaFromRequest('media')->toMediaCollection('Kids');
            }

            // Update class kids count
            $class = Classes::find($request->class_id);
            $class->increment('kids_count');

            // Assign the 'parent' role to the user
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
     * Display the specified kid.
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $kid = Kids::with('parent.user')->find($id);
        $kid->getFirstMedia('Kids');
        return contentResponse($kid, fetchOne($kid->kid_name));
    }

    /**
     * Show the form for editing the specified kid.
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(string $id)
    {
        $kid = Kids::with('parent.user')->find($id);
        $kid->getFirstMedia('Kids');
        return contentResponse($kid, fetchOne($kid->kid_name));
    }

    /**
     * Update the specified kid's details.
     * 
     * @param UpdateKid $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateKid $request, string $id)
    {
        DB::beginTransaction();

        try {
            $kid = Kids::find($id);
            $parent = Parents::find($kid->parent_id);
            $user = User::find($parent->user_id);

            // Update the kid, parent, and user details
            $kid->update($request->validated());
            if ($request->hasFile('media')) {
                $kid->addMediaFromRequest('media')->toMediaCollection('Kids');
            }
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
     * Remove the specified kid from storage.
     * 
     * @param Kids $kid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Kids $kid)
    {
        DB::beginTransaction();
        try {
            // Decrement the kid count in the associated class
            $class = Classes::findOrFail($kid->class_id);
            $class->decrement('kids_count');

            // Permanently delete the kid record
            $kid->forceDelete();
            DB::commit();
            return messageResponse('Deleted Kid Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Retrieve and display kids with birthdays in the specified month.
     * 
     * @param string $accessMonth
     * @return \Illuminate\Http\JsonResponse
     */
    public function birthdayKids($accessMonth)
    {
        $month = ($accessMonth === 'thisMonth') ? Carbon::now()->month : Carbon::now()->addMonth()->month;

        $today = Carbon::today()->startOfDay();
        $kidsBirth = Kids::whereMonth('birthdate', $month)->where('nursery_id', nursery_id())->get();

        $kids = $kidsBirth->map(function ($kid) use ($today) {
            $birthdateKid = Carbon::parse($kid->birthdate);
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

        return contentResponse($kids, fetchAll('Kids Birthday Upcoming'));
    }
}