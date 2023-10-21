<?php

namespace App\Services;

use App\Models\Room;
use App\Repositories\RoomRepository;
use App\Services\BaseService;

class RoomService extends BaseService
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */

    public function cekCode($code,$id)
    {
        // dd($code,$id);
        $room = $this->repository->getById($id)->toArray();
        
        return $room['code'] == $code;
    }
}