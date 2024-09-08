<?php

namespace App\Http\Controllers\Api\FollowUp;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowUp\FollowUpRequest;
use App\Models\Activites;
use App\Models\Kids;
use App\Models\MealAmounts;
use App\Models\Meals;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowUpController extends Controller
{
    // Variables
    private $nursery_id;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id ?? auth()->user()->employee->nursery_id;
        $this->middleware(['role:nursery_Owner|teacher|parent|permission:Manage-Classes']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FollowUpRequest $request)
    {
        DB::beginTransaction();

        try {
            $date = Carbon::today()->format('Y-m-d');

            // Validate the request and add Nursery Id
            $validatedRequest = $request->validated();
            $validatedRequest['nursery_id'] = $this->nursery_id;

            // Fetch activity and meal amounts for today
            $kidId = $validatedRequest['kid_id'];

            $activity = Activites::where('kid_id', $kidId)->whereDate('created_at', $date)->latest()->first();
            $mealAmounts = MealAmounts::where('kid_id', $kidId)->whereDate('created_at', $date)->get();

            // Update or create activity
            if ($activity) {
                $activity->update($validatedRequest);
            } else {
                $activity = Activites::create($validatedRequest);
            }

            // Process meal amounts
            if ($mealAmounts->isEmpty()) {
                foreach ($validatedRequest['meals'] as $meal) {
                    $existingMeal = $mealAmounts->firstWhere('meal_id', $meal['meal_id']);
                    if ($existingMeal) {
                        $existingMeal->update($meal);
                    } else {
                        $meal['kid_id'] = $kidId; // Add kid_id to each meal
                        $meal['nursery_id'] = $this->nursery_id; // Add nursery_id to each meal
                        MealAmounts::create($meal);
                    }
                }
            } else {
                foreach ($validatedRequest['meals'] as $meal) {
                    $meal['kid_id'] = $kidId; // Add kid_id to each meal
                    $meal['nursery_id'] = $this->nursery_id; // Add nursery_id to each meal
                    MealAmounts::create($meal);
                }
            }
            DB::commit();
            return messageResponse('Created Followup Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $kid_id, string $date)
    {
        $date = Carbon::parse($date);
        $day = $date->shortDayName; // Gets the short name of the day (e.g., "Mon" for Monday)

        $activites = Activites::with('kid')->where('kid_id', $kid_id)->whereDate('created_at', $date)->first();
        
        if (!$activites) {
            $activites = Kids::find($kid_id);
        }

        $meals = Meals::with(['amount'=>function($query) use ($kid_id){
            $query->where('kid_id', $kid_id);
        }])->where('class_id', $activites->kid->class_id ?? $activites->class_id)->where('days', $day)->get();

        $data = [
            'kid_id' => $activites->kid->id ?? $activites->id,
            'kid_name' => $activites->kid->kid_name ?? $activites->kid_name,
            'napping' => $activites->napping ?? '',
            'toilet' => $activites->toilet ?? 0,
            'diaper' => $activites->diaper ?? 0,
            'potty' => $activites->potty ?? 0,
            'mood' => $activites->mood ?? 0,
            'comment' => $activites->comment ?? '',
            'meals' => $meals->setVisible(['id', 'days', 'type', 'description', 'amount']),
        ];
        return contentResponse($data, 'Fetch');
    }
}
