<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\StudentPracticum;

class StudentPracticumController extends BaseController
{
    public function __construct(StudentPracticum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
}