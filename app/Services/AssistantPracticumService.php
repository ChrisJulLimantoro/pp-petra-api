<?php

namespace App\Services;

use App\Models\AssistantPracticum;
use App\Services\BaseService;

class AssistantPracticumService extends BaseService
{
    public function __construct(AssistantPracticum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */
}