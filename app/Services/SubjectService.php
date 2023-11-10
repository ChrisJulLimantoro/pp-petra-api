<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Validate;
use App\Models\Assistant;
use App\Services\BaseService;
use App\Models\StudentPracticum;

class SubjectService extends BaseService
{
    private $student;
    private $studentPracticum;
    private $assistant;
    private $validate;
    public function __construct(Subject $model)
    {
        parent::__construct($model);
        $this->student = new Student();
        $this->studentPracticum = new StudentPracticum();
        $this->assistant = new Assistant();
        $this->validate = new Validate();
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
    
    public function getUnapplied($subject_id, $event_id)
    {
        // get subject by id
        $subject = $this->repository->getSelectedColumn(['code','id','name'],['id' => $subject_id])->toArray();

        // get all students that took the subject
        $students = $this->student->repository()->getStudentBySubject($subject[0]['code'])->toArray();

        // get all student application
        $prac = $this->studentPracticum->repository()->getApply($subject_id)->toArray();

        $data = [];
        foreach($students as $s){
            if(in_array($s['user_id'],array_column($prac,'student_id'))) continue;
            $s['program'] = $s['program'] === 'i' ? 'Infor' : ($s['program'] === 's' ? 'SIB' : "DSA");
            $s['nrp'] = substr($s['user']['email'], 0, 9);
            $s['name'] = $s['user']['name'];
            unset($s['user']);
            $data[] = $s;
        }
        return $data;
    }

    public function getDetailedReport($subject_id, $event_id) {
        // get subject by id
        $subject = $this->repository->getSelectedColumn(['code','id','name'],['id' => $subject_id])->toArray();

        // get all students that took the subject
        $students = $this->student->repository()->getStudentBySubject($subject[0]['code'])->toArray();

        // dd($students);

        // get student application
        $studentPracs = $this->studentPracticum->repository()->getApply($subject_id, $event_id, array_column($students, 'user_id'));
        
        $applied = [];
        $unapplied = [];
        
        foreach($students as $s) {
            if ($this->validate->repository()->exist($s['user_id'], $event_id)) $s['status'] = 'Validated';
            else $s['status'] = 'Applied';
            
            $choice1 = $studentPracs->filter(function($sp) use ($s) {
                return $sp['student_id'] === $s['user_id'] && $sp['choice'] === 1;
            })->first();
            $choice2 = $studentPracs->filter(function($sp) use ($s) {
                return $sp['student_id'] === $s['user_id'] && $sp['choice'] === 2;
            })->first();

            $s['pilihan_1'] = $choice1 ? $choice1->practicum->code : null;
            $s['pilihan_2'] = $choice2 ? $choice2->practicum->code : null;
            $s['nrp'] = substr($s['user']['email'], 0, 9);
            $s['nama'] = $s['user']['name'];
            $s['program'] = $s['program'] === 'i' ? 'Infor' : ($s['program'] === 's' ? 'SIB' : "DSA");
            $s['semester'] = $s['semester'];
            unset($s['user']);

            if ($choice1) $applied[] = $s;
            else $unapplied[] = $s;
        }

        return [
            'applied' => $applied,
            'unapplied' => $unapplied,
        ];
    }
}