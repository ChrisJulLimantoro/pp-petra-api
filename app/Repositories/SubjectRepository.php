<?php

namespace App\Repositories;

use App\Models\Subject;
use App\Repositories\BaseRepository;

class SubjectRepository extends BaseRepository
{
    public function __construct(Subject $model)
    {
        parent::__construct($model);
    }

    /*
        Add new repositories
        OR
        Override existing repository here...
    */

    public function getCodes()
    {
        return $this->model->all()->pluck('code')->toArray();
    }
    public function getPracticumByCode($code)
    {
        $subject = $this->model->with(['practicums','practicums.room'])->where('code', $code)->first();
        $practicums = $subject->practicums->toArray();
        $prac = [];
        foreach($practicums as $p){
            $arr= [];
            $arr = $p;
            $arr['duration'] = $subject->duration;
            $prac[] = $arr;
        }
        return $prac;
    }
}