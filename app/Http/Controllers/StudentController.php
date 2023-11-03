<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends BaseController
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
    public function getAvailableSchedule($id,$event_id)
    {
        $data = $this->service->getAvailableSchedule($id,$event_id);
        return $this->success($data);
    }
}