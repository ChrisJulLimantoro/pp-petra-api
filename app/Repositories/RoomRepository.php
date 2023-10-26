<?php

namespace App\Repositories;

use App\Models\Room;
use App\Repositories\BaseRepository;

class RoomRepository extends BaseRepository
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    /*
        Add new repositories
        OR
        Override existing repository here...
    */

    public function getPracticum()
    {
        return $this->model->with('practicums.subject')->get();
        // return $this->model->with('practicums.subject')->get(['id as room_id','code as room_code','name as room_name','capacity as room_capacity','practicums.id as practicum_id','practicums.name as practicum_name','practicums.quota as practicum_quota','practicums.subject_id as subject_id','practicums.subject.name as subject_name','practicums.subject.code as subject_code','practicums.code as practicum_code','practicums.day as practicum_day','practicums.time as practicum_time']);
    }
}