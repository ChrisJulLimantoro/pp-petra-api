<?php

namespace App\Services;

use App\Models\MasterSchedule;
use App\Services\BaseService;

class MasterScheduleService extends BaseService
{
    public function __construct(MasterSchedule $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */
}