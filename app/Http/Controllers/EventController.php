<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Event;

class EventController extends BaseController
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
}