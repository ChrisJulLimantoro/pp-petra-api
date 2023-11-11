<?php

namespace App\Services;

use App\Models\Practicum;
use App\Models\Validate;
use App\Models\StudentPracticum;
use App\Services\BaseService;

class PracticumService extends BaseService
{
    public $studentPracticum;
    private $validate;
    public function __construct(Practicum $model)
    {
        parent::__construct($model);
        $this->studentPracticum = new StudentPracticum();
        $this->validate = new Validate();
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
        // dd($prac);
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
        $unvalidate = [];
        $validate= false;
        // dd($data);
        // process the everyclass first choice first
        foreach($data as &$d){
            $count = 0;
            foreach($d['students'] as $student){
                if($student['choice'] == 1){
                    if(in_array($student['student_id'],$unvalidate)){
                        continue;
                    }
                    if(!$this->validate->repository()->exist($student['student_id'],$event_id)){
                        $unvalidate[] = $student['student_id'];
                        continue;
                    }

                    if($count < $d['quota']){
                        $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 1]);
                        $accepted[] = $student['student_id'];
                        $count++;
                    }else{
                        $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 2]);
                    }
                    $validate = true;
                }
            }
            $d['filled'] = $count;
        }
        // process every second first
        foreach($data as $dt){
            $count = 0;
            if($student['choice'] == 2){
                if(in_array($student['student_id'],$unvalidate)){
                    continue;
                }
                if(!$this->validate->repository()->exist($student['student_id'],$event_id)){
                    $unvalidate[] = $student['student_id'];
                    continue;
                }
                if($count < ($dt['quota']-$dt['filled']) && !in_array($student['student_id'],$accepted)){
                    $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 3]);
                    $accepted[] = $student['student_id'];   
                    $count++;
                }else{
                    $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 4]);
                }
                $validate = true;
            }
        }
        if(!$validate){
            return [
                'status' => 'error',
                'msg' => 'There are an unvalidated student!'
            ];
        }
        return ['status' => 'success'];
    }

    public function getResult($practicum_id)
    {
        return $this->repository->getResult($practicum_id);
    }
}