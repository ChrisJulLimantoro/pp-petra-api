<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Subject;

class SubjectController extends BaseController
{
    public function __construct(Subject $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
}