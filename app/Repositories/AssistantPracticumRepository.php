<?php

namespace App\Repositories;

use App\Models\AssistantPracticum;
use App\Repositories\BaseRepository;

class AssistantPracticumRepository extends BaseRepository
{
    public function __construct(AssistantPracticum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new repositories
        OR
        Override existing repository here...
    */
}