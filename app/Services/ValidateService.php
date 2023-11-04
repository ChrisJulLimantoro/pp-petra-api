<?php

namespace App\Services;

use App\Models\Validate;
use App\Services\BaseService;

class ValidateService extends BaseService
{
    public function __construct(Validate $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */
}