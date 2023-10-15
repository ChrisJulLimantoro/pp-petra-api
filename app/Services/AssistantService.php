<?php

namespace App\Services;

use App\Models\Assistant;
use App\Services\BaseService;

class AssistantService extends BaseService
{
    public function __construct(Assistant $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */
}