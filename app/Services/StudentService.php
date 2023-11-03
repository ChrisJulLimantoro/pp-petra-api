<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentPracticum;
use App\Models\Subject;
use App\Services\BaseService;
use App\Services\SubjectService;

class StudentService extends BaseService
{
    private $SubjectService;
    private $studentPracticum;
    public function __construct(Student $model)
    {
        parent::__construct($model);
        $this->SubjectService = new SubjectService(new Subject());
        $this->studentPracticum = new StudentPracticum();
    }

    /*
        Add new services
        OR
        Override existing service here...
    */

    public function getAvailableSchedule($id,$event_id)
    {
        // get all the PRS
        $student = $this->getById($id);
        $prs = json_decode($student->prs,true);

        // get all the Subject Code
        $subjectCode = $this->SubjectService->getCodes();

        $availableSubject = [];

        // get all the data with that event id
        $picked = $this->studentPracticum->repository()->getByEventStudentId($id,$event_id);
        // dd($picked);

        // get all the available Subject for the student
        foreach($prs as $p){
            if(!in_array($p['code'],$subjectCode)) continue;
            $arr = [];
            $practicums = $this->SubjectService->getPracticumByCode($p['code']);
            foreach($practicums as $pr){
                if(in_array($pr['subject_id'],$picked)) continue;
                $available = true;
                foreach($prs as $s){
                    if($s['day'] == $pr['day']){
                        if($s['time'] < $pr['time'] && $s['time'] + ($s['duration']*100) > $pr['time']){
                            $available = false;
                            break;
                        }
                        if($s['time'] >= $pr['time'] && $s['time'] < $pr['time'] + ($pr['duration']*100)){
                            $available = false;
                            break;
                        }
                    }
                }
                if(!$available) continue;
                $arr[] = $pr;
            }
            $availableSubject[] = [
                'code' => $p['code'],
                'practicums' => $arr == [] ? ['No Available Schedule'] : $arr
            ];
        }
        return $availableSubject;
        
    }
}