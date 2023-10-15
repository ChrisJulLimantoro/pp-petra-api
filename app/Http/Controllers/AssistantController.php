<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Assistant;

class AssistantController extends BaseController
{
    public function __construct(Assistant $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
}