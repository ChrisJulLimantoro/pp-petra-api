<?php

namespace App\Services\test;

use App\Models\test\Student;
use App\Services\BaseService;

class StudentService extends BaseService
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */
}