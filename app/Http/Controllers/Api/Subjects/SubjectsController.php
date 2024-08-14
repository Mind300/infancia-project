<?php

namespace App\Http\Controllers\Api\Subjects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subjects\SubjectClass;
use App\Http\Requests\Subjects\SubjectRequest;
use App\Models\Subjects;
use App\Models\SubjectsClasses;
use Illuminate\Support\Facades\DB;

class SubjectsController extends Controller
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
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subjects::where('nursery_id', $this->nursery_id)->get();
        return contentResponse($subjects, fetchAll('Subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request)
    {
        $request = $request->validated();
        $request['nursery_id'] = $this->nursery_id;

        Subjects::create($request);
        return messageResponse('Created Subject Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subjects $subject)
    {
        $subject->where('nursery_id', $this->nursery_id);
        return contentResponse($subject, fetchOne($subject->title . ' Subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subjects $subject)
    {
        return contentResponse($subject, fetchOne($subject->name));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectRequest $request, Subjects $subject)
    {
        $subject->update($request->validated());
        return messageResponse('Updated Subject Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subjects $subject)
    {
        $subject->forceDelete();
        return messageResponse('Subject Deleted Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function assignSubject(SubjectClass $request)
    {
        $requestValidated = $request->validated();
        $requestValidated['nursery_id'] = $this->nursery_id;

        $subjectClass = SubjectsClasses::create($requestValidated);
        return contentResponse($subjectClass, 'Assign Subject To Class Successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function removeSubject(string $assign_id)
    {
        DB::beginTransaction();
        try {
            $subjectClass = SubjectsClasses::find($assign_id)->forceDelete();
            DB::commit();
            return messageResponse('Assign Subject To Class Successfully');
        } catch (\Throwable $error) {
            DB::rollBack();
            return messageResponse($error->getMessage(), 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function classSubject(string $id)
    {
        $classSubjects = SubjectsClasses::with('subjects')->where('class_id', $id)->get();
        return contentResponse($classSubjects, fetchOne('Subjects For Class'));
    }
}
