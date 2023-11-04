<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Validate;

class ValidateController extends BaseController
{
    public function __construct(Validate $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
}