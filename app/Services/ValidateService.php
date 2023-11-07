<?php

namespace App\Services;

use App\Models\Validate;
use App\Services\BaseService;

class ValidateService extends BaseService
{
    public function __construct(Validate $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */

    public function validating($student_id,$event_id)
    {
        $validate = $this->repository->exist($student_id,$event_id);
        if(!$validate){
            $this->repository->create([
                'student_id' => $student_id,
                'event_id' => $event_id,
                'validate' => 1,
            ]);
        }else{
            $this->repository->updateValidate($student_id,$event_id);
        }
        return true;
    }

    public function unvalidate($student_id,$event_id)
    {
        return $this->repository->unvalidate($student_id,$event_id);
    }
}