<?php

namespace App\Http\Controllers\Api\Classes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Absent\AbsentRequest;
use App\Http\Requests\Classes\ClassesRequest;
use App\Http\Requests\Classes\CreateClasses;
use App\Models\Absence;
use App\Models\Classes;
use App\Models\Kids;
use Carbon\Carbon;

class ClassesController extends Controller
{
    // Variables
    private $nursery_id;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id;
    }

    /**
     * Display a listing of the classes.
     */
    public function index()
    {
        $classes = Classes::where('nursery_id', $this->nursery_id)->get();
        return contentResponse($classes, 'Fetches Classes Successfully');
    }

    /**
     * Store a newly created class.
     */
    public function store(ClassesRequest $request)
    {
        $data = $request->validated();
        $data['nursery_id'] = $this->nursery_id;

        Classes::create($data);
        return messageResponse('Created Class Successfully');
    }

    /**
     * Display the specified class.
     */
    public function show(string $id)
    {
        $classes = Classes::with('kids')->where('nursery_id', $this->nursery_id)->find($id);
        return contentResponse($classes, fetchOne($classes->name));
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(string $id)
    {
        $classes = Classes::where('nursery_id', $this->nursery_id)->find($id);
        return contentResponse($classes, fetchOne($classes->name));
    }

    /**
     * Update the specified class.
     */
    public function update(ClassesRequest $request, Classes $class)
    {
        $data = $request->validated();

        $class->update($data);
        return messageResponse('Updated Class Successfully');
    }

    /**
     * Remove the specified class.
     */
    public function destroy(Classes $class)
    {
        $class->forceDelete();
        return messageResponse('Deleted Class Successfully');
    }

    /**
     * Remove the specified class.
     */
    public function kidsClassFetch($date = null, $class_id)
    {
        $kids = Kids::select('id', 'kid_name')->with([
            'absent'=>function($query){
                $query->select('kid_id','absent');
            },'meal_amount' => function ($query) {
                $query->select('amount', 'meal_id', 'kid_id');
            },
            'meal_amount.meal' => function ($query) {
                $query->select('id', 'type');
            },
            'activites' => function ($query) use ($date) {
                $query->whereDate('created_at', $date);
            }
        ])->where('class_id', $class_id)->get();

        if ($kids->isEmpty()) {
            $kids = Kids::with('absent')->where('class_id', $class_id)->get()->setVisible(['id', 'kid_name']);
        }

        return contentResponse($kids, fetchAll('Class Activity'));
    }

    public function absent(AbsentRequest $request)
    {
        $date = Carbon::tomorrow()->format('Y-m-d');

        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = $this->nursery_id;

        $absent = Absence::where('kid_id', $requestValidated['kid_id'])->whereDate('created_at', $date)->first();
        
        if ($absent) {
            $absent->update(['absent' => !$absent->absent]);
        } else {
            $requestValidated['absent'] = 1;
            $absent = Absence::create($requestValidated);
        }
        return messageResponse('Absent Taken Sucessfully');
    }
}
