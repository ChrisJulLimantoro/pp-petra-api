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

    public function getById($id){
        return $this->model->with([
            "student",
            "student.user:id,name,email",
            "practicum.subject:id,duration"
        ])->findOrFail($id);
    }

    public function getLimit(){
        return $this->model
        ->select('id','student_id','practicum_id','created_at')
        ->with([
            "student:user_id",
            "student.user:id,name,email",
            "practicum:id,subject_id,day,time,code,name",
            "practicum.subject:id,duration"
        ])->orderBy('created_at','desc')->limit(10)->get();
    }
    public function getByStudentId($student_id)
    {
        return $this->model->with('practicum')->where('student_id',$student_id)->get();
    }

    public function getResultByStudent($student_id,$event_id)
    {
        return $this->model->select(['student_id','event_id','practicum_id','choice'])
        ->with(['practicum:id,subject_id,code,quota,day,time','student:user_id','practicum.subject:id,name,duration','student.user:id,name,email'])
        ->where(['student_id' => $student_id,'event_id' => $event_id])
        ->orderBy('choice','asc')
        ->orderBy('created_at','asc')
        ->get();
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

    public function countValidated($sub_id)
    {
        $data = $this->model
        ->with('student.validate')
        ->where('choice',1)
        ->whereHas('practicum', function ($query) use ($sub_id) {
            $query->where('subject_id', $sub_id);
        })
        ->get()
        ->toArray();

        $count = 0;
        foreach($data as $d){
            if( $d['student']['validate'] != null) $count++;
        }
        return $count;
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

    public function exist($data)
    {
        return $this->model
        ->where('student_id',$data['student_id'])
        ->where('practicum_id',$data['practicum_id'])
        ->where('event_id',$data['event_id'])
        ->where('choice',$data['choice'])
        ->get()
        ->count() > 0;
    }

    public function checkValid($student_id,$event_id)
    {
        return $this->model
        ->where('student_id',$student_id)
        ->where('event_id',$event_id)
        ->get()
        ->count() > 0;
    }
}