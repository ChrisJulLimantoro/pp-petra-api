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

    public function getResultByStudent($event_id)
    {
        return $this->model
        ->with(['user:id,name,email','practicums.practicum.subject:id,duration'])
        ->whereHas('practicums',function($query) use ($event_id){
            $query->where('event_id',$event_id);
        })
        ->get();
    }
}