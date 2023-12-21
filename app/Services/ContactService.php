<?php

namespace App\Services;

use App\Models\Contact;
use App\Services\BaseService;

class ContactService extends BaseService
{
    public function __construct(Contact $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */
}