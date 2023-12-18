<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Practicum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function store(Request $request)
    {
        $requestFillable = $request->only($this->model->getFillable());
        ControllerUtils::validateRequest($this->model, $requestFillable);

        if(!$this->service->checkValid($requestFillable['subject_id'],$requestFillable['room_id'],$requestFillable['day'],intval($requestFillable['time']))){
            return $this->error('There is a schedule in that time and Room!',400);
        }
        $res = $this->service->create($requestFillable);
        return $this->success(
            $res,
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, $id)
    {
        $requestFillable = $request->only($this->model->getFillable());

        ControllerUtils::validateRequest($this->model, $requestFillable);

        if(!$this->service->checkValid($requestFillable['subject_id'],$requestFillable['room_id'],$requestFillable['day'],intval($requestFillable['time']),$id)){
            return $this->error('There is a schedule in that time and Room!',400);
        }

        $res = $this->service->update(
            $id,
            $requestFillable,
        );

        return $this->success($res);
    }
    public function updatePartial(Request $request, $id)
    {
        $fillableKey = [];
        foreach ($this->model->getFillable() as $field) {
            if ($request->has($field)) {
                $fillableKey[] = $field;
            }
        }

        $requestFillable = $request->only($fillableKey);

        ControllerUtils::validateRequest(
            $this->model,
            $requestFillable,
            isPatch: true,
        );

        if(!$this->service->checkValid($requestFillable['subject_id'],$requestFillable['room_id'],$requestFillable['day'],intval($requestFillable['time']),$id)){
            return $this->error('There is a schedule in that time and Room!',400);
        }

        $res = $this->service->updatePartial(
            $id,
            $requestFillable,
        );

        return $this->success($res);
    }

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

    public function getKaren($id)
    {
        return $this->success($this->service->getKaren($id));
    }

    public function deleteAll()
    {
        if ( $this->service->deleteAll() == null) {
            return $this->error('Failed to delete', 500);
        }
        return $this->success(['message' => 'Deleted'],200);
    }
}