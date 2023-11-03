<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\BaseRepository;

class StudentRepository extends BaseRepository
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    /*
        Add new repositories
        OR
        Override existing repository here...
    */

    public function getSlim(){
        return $this->model->with('user:id,name')
        ->select(['user_id','program','semester','prs','ips'])
        ->get();
    }
}