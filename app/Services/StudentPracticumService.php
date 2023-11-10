<?php

namespace App\Services;

use App\Models\StudentPracticum;
use App\Services\BaseService;

class StudentPracticumService extends BaseService
{
    public function __construct(StudentPracticum $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */

    public function getByStudentId($student_id)
    {
        return $this->repository->getByStudentId($student_id)->toArray();
    }

    public function getLimit($limit)
    {
        return $this->repository->getLimit($limit);
    }
    public function getAcceptedByStudent($student_id)
    {
        $res = $this->repository->getAcceptedByStudent($student_id)->toArray();
        $data = [];
        foreach ($res as $r){
            if ($r['practicum']['time'] < 1000) {
                $time = substr($r['practicum']['time'], 0, 1) . ":" . substr($r['practicum']['time'], 1, 2).' - ';
            } else {
                $time = substr($r['practicum']['time'], 0, 2) . ":" . substr($r['practicum']['time'], 2, 3).' - ' ;
            }
            $endTime = $r['practicum']['time'] + ($r['practicum']['subject']['duration']*100);
            if($endTime < 1000){
                $time .= substr($endTime, 0, 1) . ":" . substr($endTime, 1, 2);
            }else{
                $time .= substr($endTime, 0, 2) . ":" . substr($endTime, 2, 3);
            }

            if ($r['practicum']['day'] == "1") {
                $day = 'Senin';
            } elseif ($r['practicum']['day'] == "2") {
                $day = 'Selasa';
            } elseif ($r['practicum']['day'] == "3") {
                $day = ' Rabu';
            } elseif ($r['practicum']['day'] == "4") {
                $day = 'Kamis';
            } elseif ($r['practicum']['day'] == "5") {
                $day = 'Jumat';
            } elseif ($r['practicum']['day'] == "6") {
                $day = 'Sabtu';
            }
            $data[] = [
                'day' => $day,
                'time' => $time,
                'subject' => $r['practicum']['name'],
                'code' => $r['practicum']['code'],
                'room' => $r['practicum']['room']['name'].' ('.$r['practicum']['room']['code'].')',
            ];
        }
        return $data;
    }
}