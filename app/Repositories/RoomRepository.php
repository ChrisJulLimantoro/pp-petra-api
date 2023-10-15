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
}