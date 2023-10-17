<?php

namespace App\Services;

use App\Models\Subject;
use App\Services\BaseService;

class SubjectService extends BaseService
{
    public function __construct(Subject $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */

    public function getCodes()
    {
        return $this->repository->getCodes();
    }
    public function getPracticumByCode($code)
    {
        return $this->repository->getPracticumByCode($code);
    }
}