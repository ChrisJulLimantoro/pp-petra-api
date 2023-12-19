<?php

namespace App\Services;

use App\Models\MasterSchedule;
use App\Models\Student;
use App\Models\StudentPracticum;
use App\Models\Subject;
use App\Services\BaseService;
use App\Services\SubjectService;
use App\Models\User;

class StudentService extends BaseService
{
    private $SubjectService;
    private $studentPracticum;
    private $masterSchedule;
    private $user;
    public function __construct(Student $model)
    {
        parent::__construct($model);
        $this->SubjectService = new SubjectService(new Subject());
        $this->studentPracticum = new StudentPracticum();
        $this->masterSchedule = new MasterSchedule();
        $this->user = new User();
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

        // get schedule from Master Schedule Database
        foreach ($prs as $key => $value){
            $master = $this->masterSchedule->repository()->getSelectedColumn(['*'],['code'=>$prs[$key]['code'],'class' => $prs[$key]['class']])->toArray();
            if($master == []){
                // for random data in this development
                $prs[$key]['day'] = rand(1,6);
                $prs[$key]['time'] = rand(7,18).'30';
                $prs[$key]['duration'] = rand(2,3);
                continue;
            }else{
                $prs[$key]['day'] = $master[0]['day'];
                $prs[$key]['time'] = $master[0]['time'];
                $prs[$key]['duration'] = $master[0]['duration'];
            }
        }

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

    public function getPrs($student_id)
    {
        $res = $this->repository->getSelectedColumn(['user_id','prs'],['user_id' => $student_id],['user'])->toArray();
        $prs = json_decode($res[0]['prs'],true);
        foreach ($prs as $key => $value){
            $master = $this->masterSchedule->repository()->getSelectedColumn(['*'],['code'=>$prs[$key]['code'],'class' => $prs[$key]['class']])->toArray();
            if($master == []){
                // for random data in this development
                $prs[$key]['day'] = rand(1,6);
                $prs[$key]['time'] = rand(7,18).'30';
                $prs[$key]['duration'] = rand(2,3);
                $prs[$key]['name'] = 'dummy';
                continue;
            }else{
                $prs[$key]['day'] = $master[0]['day'];
                $prs[$key]['time'] = $master[0]['time'];
                $prs[$key]['duration'] = $master[0]['duration'];
                $prs[$key]['name'] = $master[0]['name'];
            }
        }
        $data = ['name' => $res[0]['user']['name'],'prs' => $prs,'nrp' => substr($res[0]['user']['email'],0,9)];
        return $data;
    }

    public function getByNrp($nrp)
    {
        $email = $nrp.'@john.petra.ac.id';
        $user_id = $this->user->where(['email'=>$email])->first()->id;
        // dd($user_id);
        return $this->repository->getSelectedColumn(['user_id','program','semester'],['user_id'=>$user_id],['user:id,name,email']);
    }

    public function getFormat()
    {
        $data = $this->repository->getAll();
        $res = [];
        foreach($data as $d){
            $temp = [];
            $temp['id_student'] = $d->user_id;
            $temp['name'] = $d->user->name;
            $temp['nrp'] = substr($d->user->email,0,9);
            if($d->program == 'i') $temp['program'] = 'Informatika';
            else if($d->program == 's') $temp['program'] = 'Sistem Informasi Bisnis';
            else $temp['program'] = 'Data Science & Analytics';
            $temp['semester'] = $d->semester;
            $temp['ips'] = $d->ips;
            $temp['ipk'] = $d->ipk;
            $res[] = $temp;
        }
        return $res;
    }

    public function insertPRS($data){
        $student = $this->getById($data['student_id']);
        $prs = json_decode($student->prs,true);
        $prsNew = [];
        $change = false;
        foreach($prs as $p){
            if($p['code'] == $data['code']){
                $p['class'] = $data['class'];
                $change = true;
            }
            $prsNew[] = $p;
        }
        if(!$change){
            $prsNew[] = [
                'code' => $data['code'],
                'class' => $data['class']
            ];
        }
        // dd($prsNew);
        $this->repository->updatePartial($this->repository->getById($data['student_id']),[
            'prs' => json_encode($prsNew)
        ]);
    }

    public function deletePRS($data){
        $student = $this->getById($data['student_id']);
        $prs = json_decode($student->prs,true);
        $prsNew = [];
        foreach($prs as $p){
            if($p['code'] == $data['code'] && $p['class'] == $data['class']){
                continue;
            }
            $prsNew[] = $p;
        }
        // dd($prsNew);
        $this->repository->updatePartial($this->repository->getById($data['student_id']),[
            'prs' => json_encode($prsNew)
        ]);
    }
}