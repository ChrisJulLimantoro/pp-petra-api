<?php

namespace App\Repositories;

use App\Models\Practicum;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class PracticumRepository extends BaseRepository
{
    public function __construct(Practicum $model)
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
            "subject",
            "room",
            "assistantPracticum:id,assistant_id,practicum_id", // Include practicum_id
            "assistantPracticum.assistant:user_id", // Include user_id
            "assistantPracticum.assistant.user:id,name,email",
            "studentPracticum:id,student_id,practicum_id", // Include practicum_id
            "studentPracticum.student.user:id,name,email,program"
        ])->findOrFail($id);
    }

    public function getBySubjectEvent($subject_id, $event_id)
    {
        $results = DB::table('practicums')
            ->select('practicums.id','practicums.room_id','practicums.name','practicums.code','practicums.quota','student_practicums.id as student_practicums_id','student_practicums.student_id','student_practicums.choice')
            ->join('student_practicums', 'practicums.id', '=', 'student_practicums.practicum_id')
            ->join('students', 'student_practicums.student_id', '=', 'students.user_id')
            ->where('practicums.subject_id', $subject_id)
            ->where('student_practicums.event_id', $event_id)
            ->orderBy('student_practicums.choice', 'asc')
            ->orderBy('students.ips', 'desc')
            ->orderBy('students.semester','asc')
            ->get();
    
        return $results;
    }

    public function getResult($practicum_id)
    {
        return $this->model->with(['studentPracticum.student.user:id,name,email'])
        ->whereHas('studentPracticum', function ($query) use ($practicum_id) {
            $query->where(['accepted' => '1 or 3']);
        })
        ->where('id',$practicum_id)
        ->first();
    }
}