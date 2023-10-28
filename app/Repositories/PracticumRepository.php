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
            "studentPracticum.assistant:id,user_id", // Include assistant_id and user_id
            "studentPracticum.student.user:id,name,email"
        ])->findOrFail($id);
    }
}