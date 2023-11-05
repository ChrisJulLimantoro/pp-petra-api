<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentPracticum;
use App\Models\Subject;
use App\Models\Assistant;
use App\Services\BaseService;

class SubjectService extends BaseService
{
    private $student;
    private $studentPracticum;
    private $assistant;
    public function __construct(Subject $model)
    {
        parent::__construct($model);
        $this->student = new Student();
        $this->studentPracticum = new StudentPracticum();
        $this->assistant = new Assistant();
    }

    /*
        Add new services
        OR
        Override existing service here...
    */

    public function getCodes()
    {
        return $this->repository->getCodes();
    }
    public function getPracticumByCode($code)
    {
        return $this->repository->getPracticumByCode($code);
    }
    public function getCondition()
    {
        // get all subject
        $subjects = $this->repository->getSelectedColumn(['code','id','name','duration'],[],['practicums.studentPracticum','practicums.assistantPracticum'])->toArray();

        // get all students PRS
        $prs = $this->student->repository()->getSelectedColumn(['prs'])->toArray();

        $data = [];
        foreach($subjects as $sub){
            $temp = ['id' => $sub['id'],'code' => $sub['code'],'name' => $sub['name']];
            $temp['applied'] = $this->studentPracticum->repository()->countCondition($sub['id']);
            $temp['validated'] = $this->studentPracticum->repository()->countValidated($sub['id']);
            // count all the student have prs the subject that has practicum
            $count = 0;
            foreach($prs as $p){
                $p = json_decode($p['prs'], true);
                $code = array_column($p,'code');
                if(in_array($sub['code'],$code)){
                    $count++;
                }
            }
            foreach($sub['practicums'] as $prac){
                $countSp1 = 0;
                $countSp2 = 0;
                foreach($prac['student_practicum'] as $sp){
                    if($sp['choice'] == 1){
                        $countSp1++;
                    }else{
                        $countSp2++;
                    }
                }
                $temp['practicums'][] = [
                    'id' => $prac['id'],
                    'code' => $prac['code'],
                    'quota' => $prac['quota'],
                    'time' => $prac['time'].'-'.($prac['time']+(100*$sub['duration'])),
                    'day' => $prac['day'],
                    'students' => [
                        'choice 1' => $countSp1,
                        'choice 2' => $countSp2,
                    ],
                    'assistants' => [
                        'total' => count($prac['assistant_practicum']),
                    ]];
            }
            $temp['total'] = $count;
            $data['subjects'][] = $temp;
        }
        // for assistants Condition
        $assistants = $this->assistant->repository()->getSelectedColumn(['*'],[],['assistant_practicum','user'])->toArray();
        
        foreach($assistants as $as){
            $temp = ['id' => $as['user_id'],'name' => $as['user']['name'],'email' => $as['user']['email']];
            $temp['count'] = count($as['assistant_practicum']);
            $data['assistants'][] = $temp;
        }
        array_multisort(array_column($data['assistants'],'count'),SORT_DESC,$data['assistants']);
        return $data;
    }
    public function getUnapplied($subject_id)
    {
        // get subject by id
        $subject = $this->repository->getSelectedColumn(['code','id','name'],['id' => $subject_id])->toArray();

        // get all students PRS
        $prs = $this->student->repository()->getSlim()->toArray();

        // get all student application
        $prac = $this->studentPracticum->repository()->getApply($subject_id)->toArray();

        $data = [];
        foreach($prs as $loop){
            $p = json_decode($loop['prs'],true);
            $code = array_column($p,'code');
            if(!in_array($subject[0]['code'],$code)) continue;
            if(in_array($loop['user_id'],array_column($prac,'student_id'))) continue;
            $data[] = $loop;
        }
        return $data;
    }
}