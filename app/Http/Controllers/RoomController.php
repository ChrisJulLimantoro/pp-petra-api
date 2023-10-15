<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Room;

class RoomController extends BaseController
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
}