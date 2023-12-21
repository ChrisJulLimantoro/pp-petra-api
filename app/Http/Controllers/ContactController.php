<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Contact;

class ContactController extends BaseController
{
    public function __construct(Contact $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
}