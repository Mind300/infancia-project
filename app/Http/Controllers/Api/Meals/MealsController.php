<?php

namespace App\Http\Controllers\Api\Meals;

use App\Http\Controllers\Controller;
use App\Http\Requests\Meals\MelasRequest;
use App\Models\Classes;
use App\Models\Meals;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MealsController extends Controller
{
    // Variables
    private $nursery_id;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id ?? auth()->user()->employee->nursery_id;

        $this->middleware(['permission:Meals']);
        $this->middleware(['role:nursery-Owner']);
        $this->middleware(['role:teacher'], ['only' => ['index', 'show','store', 'edit', 'update', 'destroy']]);
        $this->middleware(['role:parent'], ['only' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classesMeals = Classes::where('nursery_id', $this->nursery_id)->with('meals')->get();
        return contentResponse($classesMeals, fetchAll('Meals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MelasRequest $request)
    {
        $date = Carbon::today()->format('Y-m-d');
        $requestValidated = $request->validated();

        $meals = Meals::where('class_id', $requestValidated['class_id'])->whereDate('created_at', $date)->get();

        // Process meal amounts
        foreach ($requestValidated['meals'] as $meal) {
            $existingMeal = $meals->where('days', $meal['days'])->where('type', $meal['type'])->first();
            // dd($existingMeal);
            if ($existingMeal) {
                $existingMeal->update($meal);
            } else {
                Meals::create($meal);
            }
        }

        // $meals = Meals::create($request->validated());
        return messageResponse('Cretaed Meal Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $classMeals = Classes::where('nursery_id', $this->nursery_id)->with('meals')->find($id);
        return contentResponse($classMeals, fetchOne($classMeals->type));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meals $meal)
    {
        return contentResponse($meal, fetchOne($meal->type));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MelasRequest $request, Meals $meal)
    {
        try {
            $meal->update($request->validated());
            return messageResponse('Update Meal Successfully');
        } catch (\Throwable $error) {
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meals $meal)
    {
        $meal->forceDelete();
        return messageResponse('Deleted Meal Sucessfully');
    }
}
