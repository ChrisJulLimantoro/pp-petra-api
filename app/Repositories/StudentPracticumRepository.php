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

    public function getLimit($limit){
        return $this->model
        ->select('id','student_id','practicum_id','created_at')
        ->with([
            "student:user_id",
            "student.user:id,name,email",
            "practicum:id,subject_id,day,time,code,name",
            "practicum.subject:id,duration"
        ])->orderBy('created_at','desc')->limit($limit)->get();
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

    public function getByEvent($event_id)
    {
        return $this->model
        ->with(['practicum:id,name,code,subject_id', 'student:user_id', 'student.user:id,name,email'])
        ->where('event_id', $event_id)
        ->orWhere('event_id', null)
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

    public function getApply($subject, $event = null, $student = null, $choice = null)
    {
        $q = $this->model
        ->with([
            'practicum:id,code,day,time,subject_id',
            'practicum.subject:id,duration', 
            'student:user_id,program,semester',
            'student.user:id,email,name',
        ])
        ->select('id', 'student_id', 'practicum_id', 'event_id', 'choice')
        ->whereHas('practicum', function ($query) use ($subject) {
            $query->where('subject_id', $subject);
        });

        if ($event) $q = $q->where('event_id', $event);
        if ($student) $q = $q->whereIn('student_id', $student);
        if ($choice) $q = $q->where('choice', $choice);

        return $q->get();
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

    public function getAcceptedByStudent($student_id)
    {
        // DB::enableQueryLog();
        return $this->model
        ->select(['practicum_id','accepted','student_id','id'])
        ->with(['practicum:id,subject_id,code,name,day,time,room_id','practicum.subject:id,duration','practicum.room:id,name,code'])
        ->where('accepted','=',1,'or','accepted','=',3)
        ->where('student_id',$student_id)
        ->get();
        // dd(DB::getQueryLog());
    }

    public function assignManual($data)
    {
        $this->model->create([
            'student_id' => $data['student_id'],
            'practicum_id' => $data['practicum_id'],
            'event_id' => null,
            'choice' => 0,
            'accepted' => 1,
        ]);
        return true;
    }

    public function getCountAccepted($practicum_id){
        // DB::enableQueryLog();
        $query =  $this->model
        ->where('practicum_id','=',$practicum_id)
        ->where('accepted','=',1)
        ->orWhere('accepted','=',3)
        ->get()
        ->count();
        // dd(DB::getQueryLog());
        return $query;
    }
}