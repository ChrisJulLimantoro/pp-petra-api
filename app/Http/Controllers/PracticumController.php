<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Practicum;
use Illuminate\Support\Facades\Validator;

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

    public function generateResult($subject,$event)
    {
        $valid = Validator::make([
            'subject_id' => $subject, 'event_id' => $event
        ], 
        [
            'subject_id' => 'required|exists:subjects,id',
            'event_id' => 'required|exists:events,id'
        ],[
            'subject_id.required' => 'Subject id is required',
            'subject_id.exists' => 'Subject id is not exists',
            'event_id.required' => 'Event id is required',
            'event_id.exists' => 'Event id is not exists'
        ]);
        if($valid->fails()){
            return $this->error($valid->errors());
        }
        return $this->success($this->service->generateResult($subject,$event));
    }

    public function getResult($practicum)
    {
        $valid = Validator::make([
            'practicum_id'=> $practicum
        ], 
        [
            'practicum_id' => 'required|exists:practicums,id'
        ],[
            'practicum_id.required'=> 'practicum is required!',
            'practicum_id.exists'=> 'practicum is not exists!'
        ]);
        if($valid->fails()){
            return $this->error($valid->errors());
        }
        return $this->success($this->service->getResult($practicum));
    }

    public function getKaren()
    {
        return $this->success($this->service->getKaren());
    }
}