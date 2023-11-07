<?php

namespace App\Services;

use App\Http\Controllers\MailController;
use App\Models\StudentPracticum;
use App\Models\Validate;
use App\Services\BaseService;

class ValidateService extends BaseService
{
    private $studentPracticum;
    private $mailController;
    public function __construct(Validate $model)
    {
        parent::__construct($model);
        $this->studentPracticum = new StudentPracticum();
        $this->mailController = new MailController();
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

        // sending mail
        $practicums = $this->studentPracticum->repository()
        ->getResultByStudent($student_id,$event_id)
        ->toArray();
        // dd($practicums);
        $data = ['to' => $practicums[0]['student']['user']['email'],'name' => $practicums[0]['student']['user']['name']];
        foreach($practicums as $pr){
            $jadwal = "";

            if($pr['practicum']['day'] == 1){
                $jadwal .= "Senin, ";
            }else if($pr['practicum']['day'] == 2){
                $jadwal .= "Selasa, ";
            }else if($pr['practicum']['day'] == 3){
                $jadwal .= "Rabu, ";
            }else if($pr['practicum']['day'] == 4){
                $jadwal .= "Kamis, ";
            }else if($pr['practicum']['day'] == 5){
                $jadwal .= "Jumat, ";
            }else if($pr['practicum']['day'] == 6){
                $jadwal .= "Sabtu, ";
            }

            $jadwal .= $pr['practicum']['time'].' - '.($pr['practicum']['time']+(100*$pr['practicum']['subject']['duration'])).' WIB';
            $data['apply'][] = [
                'practicum' => $pr['practicum']['subject']['name'],
                'code' => $pr['practicum']['code'],
                'jadwal' => $jadwal,
                'choice' => $pr['choice'],
            ];

        }
        $this->mailController->sendApply($data);
        return true;
    }

    public function unvalidate($student_id,$event_id)
    {
        return $this->repository->unvalidate($student_id,$event_id);
    }
}