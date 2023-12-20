<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\AssistantPracticum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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

    public function store(Request $request){
        $valid = Validator::make($request->only(['assistant_id','practicum_id']),
        [
            'assistant_id' => 'required|exists:assistants,user_id',
            'practicum_id' => 'required|exists:practicums,id'
        ],[
            'assistant_id.required' => 'Assistant id is required',
            'assistant_id.exists' => 'Assistant id is not exists',
            'practicum_id.required' => 'Practicum id is required',
            'practicum_id.exists' => 'Practicum id is not exists'
        ]);

        if($this->service->exist($request->only(['assistant_id','practicum_id']))){
            return $this->error('Assistant practicum already exists');
        }

        if($valid->fails()){
            return $this->error($valid->errors());
        }
        return $this->success($this->service->create($request->only(['assistant_id','practicum_id'])));
    }
}