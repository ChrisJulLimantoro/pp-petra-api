<?php

namespace App\Repositories;

use App\Models\Practicum;
use App\Repositories\BaseRepository;

class PracticumRepository extends BaseRepository
{
    public function __construct(Practicum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new repositories
        OR
        Override existing repository here...
    */
}