<?php

namespace App\Repositories;

use App\Models\StudentPracticum;
use App\Repositories\BaseRepository;

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
}