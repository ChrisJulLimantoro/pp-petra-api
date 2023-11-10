<?php

namespace App\Repositories;

use App\Models\Assistant;
use App\Repositories\BaseRepository;

class AssistantRepository extends BaseRepository
{
    public function __construct(Assistant $model)
    {
        parent::__construct($model);
    }

    /*
        Add new repositories
        OR
        Override existing repository here...
    */

    public function updatePartial($assistant,$data){
        foreach ($data as $key => $value) {
            if($value == "") $value = null;
            $assistant->{$key} = $value;
        }

        $assistant->save();

        return $assistant->refresh();
    }
    public function update($assistant,$data){
        foreach ($data as $key => $value) {
            if($value == "") $value = null;
            $assistant->{$key} = $value;
        }

        $assistant->save();

        return $assistant->refresh();
    }
}