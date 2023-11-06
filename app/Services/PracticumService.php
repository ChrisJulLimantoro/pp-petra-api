<?php

namespace App\Services;

use App\Models\Practicum;
use App\Models\StudentPracticum;
use App\Services\BaseService;

class PracticumService extends BaseService
{
    public $studentPracticum;
    public function __construct(Practicum $model)
    {
        parent::__construct($model);
        $this->studentPracticum = new StudentPracticum();
    }

    /*
        Add new services
        OR
        Override existing service here...
    */

    public function getKaren()
    {
        $res = $this->repository->getKaren()->toArray();
        $data = [];
        foreach($res as $r){
            $data[] = [
                'name' => $r['subject']['name'],
                'day' => $r['day'],
                'time' => $r['time'].'-'.($r['time']+($r['subject']['duration']*100)),
                'code' => $r['code'],
                'assistants' => count($r['assistant_practicum']),
                'quota' => ceil($r['quota']/8)
            ];
        }
        return $data;
    }
    public function generateResult($subject_id,$event_id)
    {
        // get practicum by id and event held on its studentPracticum
        $prac = $this->repository->getBySubjectEvent($subject_id,$event_id);

        // split by code
        $data = [];
        foreach($prac as $prac){
            if(!key_exists($prac->code,$data)){
                $data[$prac->code]['id'] = $prac->id;
                $data[$prac->code]['quota'] = $prac->quota;
            }
            $data[$prac->code]['students'][] = [
                'student_practicum_id' => $prac->student_practicums_id,
                'student_id' => $prac->student_id,
                'choice' => $prac->choice
            ];
        }
        $accepted = [];
        // process the everyclass first choice first
        foreach($data as $data){
            $count = 0;
            foreach($data['students'] as $student){
                if($student['choice'] == 1){
                    if($count < $data['quota']){
                        $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 1]);
                        $accepted[] = $student['student_id'];
                        $count++;
                    }else{
                        $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 2]);
                    }
                }
            }
            $data['filled'] = $count;
        }
        
        // process every second first
        foreach($data as $data){
            $count = 0;
            if($student['choice'] == 2){
                if($count < ($data['quota']-$data['filled']) && !in_array($student['student_id'],$accepted)){
                    $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 3]);
                    $accepted[] = $student['student_id'];   
                    $count++;
                }else{
                    $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 4]);
                }
            }
        }
        return ['status' => 'success'];
    }

    public function getResult($practicum_id)
    {
        return $this->repository->getResult($practicum_id);
    }
}