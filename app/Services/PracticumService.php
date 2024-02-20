<?php

namespace App\Services;

use App\Models\Practicum;
use App\Models\Validate;
use App\Models\StudentPracticum;
use App\Models\Subject;
use App\Services\BaseService;
use App\Models\Event;

class PracticumService extends BaseService
{
    public $studentPracticum;
    private $validate;
    private $subject;
    private $event;
    public function __construct(Practicum $model)
    {
        parent::__construct($model);
        $this->studentPracticum = new StudentPracticum();
        $this->subject = new Subject();
        $this->validate = new Validate();
        $this->event = new Event();
    }

    /*
        Add new services
        OR
        Override existing service here...
    */

    public function getKaren($id)
    {
        $res = $this->repository->getKaren()->toArray();
        $dataA = [];
        $dataL = [];
        foreach($res as $r){
            $ajar = false;
            $ap = [];
            foreach($r['assistant_practicum'] as $a){
                if($a['assistant_id'] == $id){
                    $ajar = true;
                    $ap = $a;
                    break;
                }
            }
            if($ajar){
                $dataA[] = [
                    'name' => $r['subject']['name'],
                    'day' => $r['day'],
                    'time' => $r['time'].'-'.($r['time']+($r['subject']['duration']*100)),
                    'code' => $r['code'],
                    'assistants' => count($r['assistant_practicum']),
                    'quota' => ceil($r['quota']/8),
                    'assistant_practicum_id' => $ap['id'],
                    'practicum_id' => $r['id']
                ];
            }else{
                $dataL[] = [
                    'name' => $r['subject']['name'],
                    'day' => $r['day'],
                    'time' => $r['time'].'-'.($r['time']+($r['subject']['duration']*100)),
                    'code' => $r['code'],
                    'assistants' => count($r['assistant_practicum']),
                    'quota' => ceil($r['quota']/8),
                    'practicum_id' => $r['id']
                ];
            }
        }
        $data = ['ajar' => $dataA, 'lowongan' => $dataL];
        return $data;
    }
    public function generateResult($subject_id,$event_id)
    {
        // get practicum by id and event held on its studentPracticum
        $prac = $this->repository->getBySubjectEvent($subject_id,$event_id);
        // dd($prac->toArray());
        // split by code
        $data = [];
        foreach($prac as $p){
            if(!key_exists($p->code,$data)){
                $data[$p->code]['id'] = $p->id;
                $data[$p->code]['quota'] = $p->quota;
                $data[$p->code]['students'] = [];
            }
            if($p->accepted != 0) continue;
            $data[$p->code]['students'][] = [
                'student_practicum_id' => $p->student_practicums_id,
                'student_id' => $p->student_id,
                'choice' => $p->choice,
                'accepted' => $p->accepted
            ];
        }
        $accepted = [];
        $unvalidate = [];
        $validate= false;
        // dd($data);
        // process the everyclass first choice first
        foreach($data as &$d){
            $count = $this->studentPracticum->repository()->getCountAccepted($d['id']);
            foreach($d['students'] as $student){
                if($student['choice'] == 1){
                    if(in_array($student['student_id'],$unvalidate)){
                        $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 6]);
                        continue;
                    }
                    if(!$this->validate->repository()->exist($student['student_id'],$event_id)){
                        $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 6]);
                        $unvalidate[] = $student['student_id'];
                        continue;
                    }
                    // dd($count,$d);
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
        // dd($data);
        // process every second choice
        foreach($data as $dt){
            $count = 0;
            foreach($dt['students'] as $student){
                if($student['choice'] == 2){
                    if(in_array($student['student_id'],$unvalidate)){
                        $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 6]);
                        continue;
                    }
                    if(!$this->validate->repository()->exist($student['student_id'],$event_id)){
                        $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($student['student_practicum_id']), ['accepted' => 6]);
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

    public function checkValid($subject_id,$room_id,$day,$time,$prac_id = null)
    {
        $duration_now = $this->subject->repository()->getById($subject_id)->duration;
        $prac = $this->repository->getSelectedColumn(['*'],['room_id' => $room_id, 'day' => $day],['subject']);
        foreach($prac as $p){
            if($p->id == $prac_id) continue;
            if($p->time < $time && $p->time + ($p->subject->duration*100) > $time){
                return false;
            }else if ($p->time == $time){
                return false;
            }else if($p->time > $time && $p->time < $time + ($duration_now*100)){
                return false;
            }
        }
        return true;
    }

    public function deleteAll()
    {
        $prac = $this->repository->getSelectedColumn(['id']);
        foreach($prac as $p){
            $this->repository->delete($p);
        }
        return true;
    }

    public function lowerQuota($id,$quota)
    {
        $prac = $this->repository->getById($id);
        if($prac->quota < $quota){
            return false;
        }
        return true;
    }

    public function regenerate($event_id,$prac_id)
    {
        $subject = $this->repository->getById($prac_id)->subject_id;
        $prac = $this->repository->getBySubjectEvent($subject,$event_id);
        $data = [];
        foreach($prac as $p){
            if(!key_exists($p->student_id,$data)){
                $data[$p->student_id]['id'] = $p->student_id;
                $data[$p->student_id]['choices'] = [];
            }
            $data[$p->student_id]['choices'][] = [
                'student_practicum_id' => $p->student_practicums_id,
                'practicum_id' => $p->id,
                'choice' => $p->choice,
                'accepted' => $p->accepted
            ];
        }
        foreach ($data as $d){
            if($d['choices'][0]['choice'] == 0) continue;
            else{
                foreach ($d['choices'] as $dt){
                    $this->studentPracticum->repository()->updatePartial($this->studentPracticum->repository()->getById($dt['student_practicum_id']),['accepted' => 0]);
                }
            }
        }
        // after we reset all the correspodent now we can generate the rsult
        return $this->generateResult($subject,$event_id);
    }
}