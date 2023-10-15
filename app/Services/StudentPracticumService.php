<?php

namespace App\Services;

use App\Models\StudentPracticum;
use App\Services\BaseService;

class StudentPracticumService extends BaseService
{
    public function __construct(StudentPracticum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */
}