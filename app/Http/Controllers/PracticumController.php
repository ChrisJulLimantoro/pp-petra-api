<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Practicum;

class PracticumController extends BaseController
{
    public function __construct(Practicum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
}