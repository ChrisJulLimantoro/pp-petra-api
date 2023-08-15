<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\BaseController;
use App\Models\test\Student;

class StudentController extends BaseController
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
}