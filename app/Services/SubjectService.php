<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentPracticum;
use App\Models\Subject;
use App\Services\BaseService;

class SubjectService extends BaseService
{
    private $student;
    private $studentPracticum;
    public function __construct(Subject $model)
    {
        parent::__construct($model);
        $this->student = new Student();
        $this->studentPracticum = new StudentPracticum();
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
        $subjects = $this->repository->getSelectedColumn(['code','id','name'])->toArray();

        // get all students PRS
        $prs = $this->student->repository()->getSelectedColumn(['prs'])->toArray();

        $data = [];
        foreach($subjects as $sub){
            $sub['applied'] = $this->studentPracticum->repository()->countCondition($sub['id']);
            // count all the student have prs the subject that has practicum
            $count = 0;
            foreach($prs as $p){
                $p = json_decode($p['prs'], true);
                $code = array_column($p,'code');
                if(in_array($sub['code'],$code)){
                    $count++;
                }
            }
            $sub['total'] = $count;
            $data[] = $sub;
        }

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