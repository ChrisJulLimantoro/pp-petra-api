<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\AssistantPracticum;

class AssistantPracticumController extends BaseController
{
    public function __construct(AssistantPracticum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
}