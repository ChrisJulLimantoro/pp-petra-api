<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\MasterSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterScheduleController extends BaseController
{
    public function __construct(MasterSchedule $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
    public function bulkInsert(Request $request)
    {
        $data = $request->data;

        foreach($data as $d){
            $valid = Validator::make([
                'code' => $d['code'],
                'day' => $d['day'],
                'time' => $d['time'],
                'duration' => $d['duration'],
                'name' => $d['name'],
                'class' => $d['class']
            ],[
                'code' => 'required|string',
                'day' => 'required|integer',
                'time' => 'required|integer',
                'duration' => 'required|integer',
                'name' => 'required|string',
                'class' => 'required|string'
            ],[
                'code.required' => 'Kode praktikum harus diisi',
                'code.string' => 'Kode praktikum harus berupa string',
                'day.required' => 'Hari praktikum harus diisi',
                'day.integer' => 'Hari praktikum harus berupa angka',
                'time.required' => 'Waktu praktikum harus diisi',
                'time.integer' => 'Waktu praktikum harus berupa angka',
                'duration.required' => 'Durasi praktikum harus diisi',
                'duration.integer' => 'Durasi praktikum harus berupa angka',
                'name.required' => 'Nama praktikum harus diisi',
                'name.string' => 'Nama praktikum harus berupa string',
                'class.required' => 'Kelas praktikum harus diisi',
                'class.string' => 'Kelas praktikum harus berupa string'
            ]);
            if($valid->fails()){
                return $this->error($valid->errors(),422);
            }

            if($this->model->repository()->exist($d['code'],$d['class'])){
                $master = $this->model->repository()->getSelectedColumn(['*'],['code' => $d['code'],'class' => $d['class']])->first();
                $this->service->update($master->id,$d);
            }else{
                $this->service->create($d);
            }
        }
        return $this->success('Bulk Insert Master Schedule Successful');
    }
}