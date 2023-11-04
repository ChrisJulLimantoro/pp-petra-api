<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Student;
use App\Models\StudentPracticum;
use App\Models\Validate;
use App\Utils\HttpResponseCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentPracticumController extends BaseController
{
    private $validate;
    public function __construct(StudentPracticum $model)
    {
        parent::__construct($model);
        $this->validate = new Validate();
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

        // cek if student already validate
        if($this->validate->repository()->exist($requestFillable['student_id'],$requestFillable['event_id'])){
            return $this->error('Student already validate',400);
        }

        // cek if the same studentPracticum is already there
        if($this->model->repository()->exist($requestFillable)){
            return $this->error('Student already apply this practicum',400);
        };

        $res = $this->service->create($requestFillable);

        return $this->success(
            $res,
            HttpResponseCode::HTTP_CREATED
        );
    }

    public function update(Request $request, $id){
        $requestFillable = $request->only($this->model->getFillable());

        ControllerUtils::validateRequest($this->model, $requestFillable);

        // cek if student already validate
        if($this->validate->repository()->exist($requestFillable['student_id'],$requestFillable['event_id'])){
            return $this->error('Student already validate',400);
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

        // cek if student already validate
        if($this->validate->repository()->exist($requestFillable['student_id'],$requestFillable['event_id'])){
            return $this->error('Student already validate',400);
        }

        $res = $this->service->updatePartial(
            $id,
            $requestFillable,
        );

        return $this->success($res);
    }

    public function destory($id)
    {
        $data = $this->model->repository()->getById($id);
        // cek if student already validate
        if($this->validate->repository()->exist($data->student_id,$data->event_id)){
            return $this->error('Student already validate',400);
        }
        
        $this->service->delete($id);

        return $this->success(
            null,
            200
        );
    }
    public function getByStudentId($student_id)
    {
        $valid = Validator::make(['student_id' => $student_id], [
            'student_id' => 'required|exists:students,user_id'
        ],[
            'student_id.required' => 'Student ID is required',
            'student_id.exists' => 'Student ID is not exists'
        ]);

        if ($valid->fails()) {
            return $this->error($valid->errors()->first(), 400);
        }
        return $this->success($this->service->getByStudentId($student_id));
    }

}  