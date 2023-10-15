<?php

namespace App\Services;

use App\Models\Practicum;
use App\Services\BaseService;

class PracticumService extends BaseService
{
    public function __construct(Practicum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */
}