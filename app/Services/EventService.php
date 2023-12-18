<?php

namespace App\Services;

use App\Models\Event;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

class EventService extends BaseService
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */

    public function getActiveEvent()
    {
        $events = $this->repository->getActiveEvent();
        $time = Carbon::now();
        $time->toString();
        foreach ($events as $e){
            if(Carbon::createFromFormat('Y-m-d',$e->start_date)->isPast()){
                return $e;
            }
        }
    }

    public function checkRegenerate()
    {
        $events = $this->repository->getAll();
        $regenerate = [];
        foreach($events as $e){
            if($e->generated == 1){
                $regenerate[] = $e->id;
            }
        }
        return $regenerate;
    }

    public function deleteAll()
    {
        $events = $this->repository->getAll();
        foreach ($events as $e){
            $this->repository->delete($e);
        }
        return true;
    }
}