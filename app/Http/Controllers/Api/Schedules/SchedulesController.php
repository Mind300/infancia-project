<?php

namespace App\Http\Controllers\Api\Schedules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\ScheduleRequest;
use App\Models\Classes;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchedulesController extends Controller
{
    // Variables
    private $nursery_id;
    private $class_id;

    /**
     * Construct a instance of the resource.
     */
    public function __construct()
    {
        $this->nursery_id = auth()->user()->nursery->id ?? auth()->user()->parent->nursery_id ?? auth()->user()->employee->nursery_id;
        // $this->middleware(['permission:Manage-Classes']);
        // $this->middleware(['role:nursery_Owner']);
        // $this->middleware(['role:teacher'], ['only' => ['index', 'show', 'store']]);
        // $this->middleware(['role:parent'], ['only' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedule = Schedule::where('nursery_id', $this->nursery_id)->get();
        return contentResponse($schedule, fetchAll('Schedules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request)
    {
        DB::beginTransaction();
        try {
            $requestValidated = $request->validated();
            $requestValidated['nursery_id'] = $this->nursery_id;
            $schedule = Schedule::create($requestValidated);
            DB::commit();
            return messageResponse('Added Schedule Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $class_id, string $day)
    {
        $class = Classes::find($class_id);
        $class_schedule = $class->subjects->map(function ($subject) use ($class, $day) {
            return
                [
                    'subject' => $subject->subjects,
                    'subject_content' => $class?->schedules?->where('days', $day)->where('subject_id', $subject->subject_id)->first()
                ];
        });
        return contentResponse($class_schedule, fetchOne('Schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classes $class)
    {
        return contentResponse($class, fetchOne('Schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        DB::beginTransaction();
        try {
            $requestValidated = $request->validated();
            $requestValidated['nursery_id'] = $this->nursery_id;
            $schedule->update($requestValidated);
            DB::commit();
            return messageResponse('Updated Schedule Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->forceDelete();
        return messageResponse('Deleted Schedule Successfully');
    }
}
