<?php

namespace App\Repositories;

use App\Models\MasterSchedule;
use App\Repositories\BaseRepository;

class MasterScheduleRepository extends BaseRepository
{
    public function __construct(MasterSchedule $model)
    {
        parent::__construct($model);
    }

    /*
        Add new repositories
        OR
        Override existing repository here...
    */
    public function exist($code,$class)
    {
        return $this->model->where('code',$code)->where('class',$class)->get()->count() > 0;
    }
}