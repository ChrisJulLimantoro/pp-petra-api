<?php

namespace App\Repositories;

use App\Models\StudentPracticum;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class StudentPracticumRepository extends BaseRepository
{
    public function __construct(StudentPracticum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new repositories
        OR
        Override existing repository here...
    */

    public function getByStudentId($student_id)
    {
        return $this->model->with('practicum')->where('student_id',$student_id)->get();
    }

    public function getByEventStudentId($student_id,$event_id)
    {
        return $this->model
        ->with('practicum')
        ->where('student_id',$student_id)
        ->where('event_id',$event_id)
        ->get()
        ->pluck('practicum.subject_id')
        ->toArray();
    }

    public function countCondition($sub_id)
    {
        return $this->model
        ->where('choice',1)
        ->whereHas('practicum', function ($query) use ($sub_id) {
            $query->where('subject_id', $sub_id);
        })->count();
    }

    public function getApply($subject)
    {
        return $this->model
            ->with(['practicum:id,subject_id','practicum.subject:id,code']) // Eager load the 'practicum' relationship
            ->whereHas('practicum', function ($query) use ($subject) {
                $query->where('subject_id', $subject);
            })
            ->where('choice', 1)
            ->select('student_id', 'practicum_id')
            ->get();
    }

    public function exist($where)
    {
        return $this->model
        ->where($where)
        ->get()
        ->count() > 0;
    }
}