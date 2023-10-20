<?php

namespace App\Services;

use App\Models\Event;
use App\Services\BaseService;

class EventService extends BaseService
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */
}