<?php

namespace App\Services;

use App\Models\MasterSchedule;
use App\Services\BaseService;

class MasterScheduleService extends BaseService
{
    public function __construct(MasterSchedule $model)
    {
        parent::__construct($model);
    }

    /*
        Add new services
        OR
        Override existing service here...
    */
    public function getFormat()
    {
        $data = $this->repository->getAll();
        $result=[];
        foreach($data as $x){
            if($x['day']==1){
                $day="Senin";
            }else if($x['day']==2){
                $day="Selasa";
            }else if($x['day']==3){
                $day="Rabu";
            }else if($x['day']==4){
                $day="Kamis";
            }else if($x['day']==5){
                $day="Jumat";
            }else if($x['day']==6){
                $day="Sabtu";
            };

            $endHour = strval($x['time'] + $x['duration']*100);
                if (strlen($endHour) == "3") {
                    $endHour = substr($endHour, 0, 1) . ":" . substr($endHour, 1, 2);
                } else if (strlen($endHour) == "4") {
                    $endHour = substr($endHour, 0, 2) . ":" . substr($endHour, 2, 3);
                }
            $startHour = strval($x['time']);
            if (strlen($startHour) == "3") {
                $startHour= strval($startHour);
                $startHour = substr($startHour, 0, 1) . ":" . substr($startHour, 1, 2);
            } else if (strlen($startHour) == "4") {
                $startHour= strval($startHour);
                $startHour = strval(substr($startHour, 0, 2) . ":" . substr($startHour, 2, 3));
            }


            $time = $startHour . " - " . $endHour;

            array_push($result,[
                "kode" => $x['code'],
                "mata_kuliah" => $x['name'],
                "kelas" => $x['class'],
                "hari" => $day,
                "jam" => $time,
                "id" => $x['id']
            ]);
        }
        return $result;
    }
}