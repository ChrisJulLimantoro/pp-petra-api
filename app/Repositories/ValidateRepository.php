<?php

namespace App\Repositories;

use App\Models\Validate;
use App\Repositories\BaseRepository;

class ValidateRepository extends BaseRepository
{
    public function __construct(Validate $model)
    {
        parent::__construct($model);
    }

    /*
        Add new repositories
        OR
        Override existing repository here...
    */

    public function exist($student_id,$event_id)
    {
        return $this->model
        ->where('student_id', $student_id)
        ->where('event_id', $event_id)
        ->where('validate', 1)
        ->get()
        ->count() > 0;
    }

    public function existCheck($student_id,$event_id)
    {
        return $this->model
        ->where('student_id', $student_id)
        ->where('event_id', $event_id)
        ->get()
        ->count() > 0;
    }

    public function updateValidate($student_id,$event_id)
    {
        $validate_data = $this->model->where('student_id', $student_id)
        ->where('event_id', $event_id)
        ->first();
        $validate_data->validate = 1;
        $validate_data->save();
        return true;
    }

    public function unvalidate($student_id,$event_id)
    {
        $validate_data = $this->model->where('student_id', $student_id)
        ->where('event_id', $event_id)
        ->first();
        $validate_data->validate = 0;
        $validate_data->save();
        return true;
    }
}