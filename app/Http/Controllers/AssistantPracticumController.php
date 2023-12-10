<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\AssistantPracticum;
use Illuminate\Support\Facades\Validator;

class AssistantPracticumController extends BaseController
{
    public function __construct(AssistantPracticum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */

    public function store($request){
        $valid = Validator::make($request->all(), 
        [
            'assistant_id' => 'required|exists:assistants,id',
            'practicum_id' => 'required|exists:practicums,id'
        ],[
            'assistant_id.required' => 'Assistant id is required',
            'assistant_id.exists' => 'Assistant id is not exists',
            'practicum_id.required' => 'Practicum id is required',
            'practicum_id.exists' => 'Practicum id is not exists'
        ]);
        if($valid->fails()){
            return $this->error($valid->errors());
        }
        return $this->success($this->service->store($request));
    }
}