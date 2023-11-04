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
        ->get()
        ->count() > 0;
    }
}