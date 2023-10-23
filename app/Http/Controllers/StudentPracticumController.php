<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Student;
use App\Models\StudentPracticum;
use Illuminate\Support\Facades\Validator;

class StudentPracticumController extends BaseController
{
    public function __construct(StudentPracticum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */

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