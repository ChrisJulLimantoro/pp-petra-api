<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Validate;
use Illuminate\Support\Facades\Validator;

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

    public function validating($student_id,$event_id){
        $valid = Validator::make(['student_id' => $student_id,'event_id' => $event_id],[
            'student_id' => 'required|exists:students,user_id',
            'event_id' => 'required|exists:events,id',
        ],[
            'student_id.required' => 'Student ID is required',
            'student_id.exists' => 'Student ID is not exists',
            'event_id.required' => 'Event ID is required',
            'event_id.exists' => 'Event ID is not exists',
        ]);
        if($valid->fails()){
            return $this->error($valid->errors()->first(), 422);
        }
        if($this->model->repository()->exist($student_id,$event_id)){
            return $this->error('Student has validated', 422);
        }
        return $this->success($this->service->validating($student_id,$event_id));
    }

    public function unvalidate($student_id,$event_id){
        $valid = Validator::make(['student_id' => $student_id,'event_id' => $event_id],[
            'student_id' => 'required|exists:students,user_id',
            'event_id' => 'required|exists:events,id',
        ],[
            'student_id.required' => 'Student ID is required',
            'student_id.exists' => 'Student ID is not exists',
            'event_id.required' => 'Event ID is required',
            'event_id.exists' => 'Event ID is not exists',
        ]);
        if($valid->fails()){
            return $this->error($valid->errors()->first(), 422);
        }
        if(!$this->model->repository()->exist($student_id,$event_id)){
            return $this->error('Student not yet validated', 422);
        }
        return $this->success($this->service->unvalidate($student_id,$event_id));
    }
}