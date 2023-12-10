<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Models\UserRole;
use App\Utils\HttpResponseCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends BaseController
{
    private $user;
    public function __construct(Student $model)
    {
        parent::__construct($model);
        $this->user = new User();
    }

    public function store(Request $request)
    {
        $data = $request->only(['email','name','ipk','ips','prs','semester','program']);

        // cek if user exist
        if($this->user->where('email',$data['email'])->get()->count() == 0){
            $user = User::create(['email' => $data['email'],'name' => $data['name']]);
            UserRole::create(['user_id'=>$user->id,'role_id'=>Role::where('slug','student')->first()->id]);
        }else{
            $user = User::where('email',$data['email'])->first();
            // cek if role exist
            if(UserRole::where('user_id',$user->id)->where('role_id',Role::where('slug','student')->first()->id)->get()->count() == 0){
                UserRole::create(['user_id'=>$user->id,'role_id'=>Role::where('slug','student')->first()->id]);
            }
        }

        $data['user_id'] = $user->id;
        ControllerUtils::validateRequest($this->model, $data);

        $res = $this->service->create($data);

        return $this->success(
            $res,
            HttpResponseCode::HTTP_CREATED
        );
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */
    public function getAvailableSchedule($id,$event_id)
    {
        $data = $this->service->getAvailableSchedule($id,$event_id);
        return $this->success($data);
    }

    public function getPrs($student_id){
        return $this->success($this->service->getPrs($student_id));
    }

    public function getByNrp($nrp){
        $nrp = strtolower($nrp);
        // dd($nrp);
        return $this->success($this->service->getByNrp($nrp));
    }

    public function bulkInsert(Request $request)
    {
        $data = $request->data;
        // dd($data);
        foreach ($data as $d){
            // validator
            $valid = Validator::make($d,[
                'email' => 'required|email',
                'name' => 'required',
                'ips' => 'required',
                'ipk' => 'required',
                'prs' => 'required',
                'semester' => 'required',
                'program' => 'required',
                'periode' => 'string'
            ],[
                'email.required' => 'Email is required',
                'email.email' => 'Email is not valid',
                'name.required' => 'Name is required',
                'ips.required' => 'IPS is required',
                'ipk.required' => 'IPK is required',
                'prs.required' => 'PRS is required',
                'semester.required' => 'Semester is required',
                'program.required' => 'Program is required',
                'periode' => 'Periode must be String!'
            ]);
            if($valid->fails()){
                return $this->error($valid->errors(),400);
            }
            // cek if user exist
            if($this->user->where('email',$d['email'])->get()->count() == 0){
                $user = User::create(['email' => $d['email'],'name' => $d['name']]);
                UserRole::create(['user_id'=>$user->id,'role_id'=>Role::where('slug','student')->first()->id]);
            }else{
                $user = User::where('email',$d['email'])->first();
            }
            // cek if student exist
            if($this->model->where('user_id',$user->id)->get()->count() == 0){
                $this->service->create([
                    'user_id'=>$user->id,
                    'ips'=>$d['ips'],
                    'ipk' => $d['ipk'],
                    'prs' => json_encode($d['prs']),
                    'semester' => $d['semester'],
                    'program' => $d['program'],
                    'last_periode' => strtolower($d['periode'])
                ]);
            }else{
                $student = $this->service->getbyId($user->id);
                $prsNew = [];
                if($student->last_periode == strtolower($d['periode'])){
                    $prsNew = json_decode($student->prs,true);
                    $temp = [];
                    foreach($prsNew as $p){
                        $temp[$p['code']] = $p['class'];
                    }
                    foreach($d['prs'] as $p){
                        if(!array_key_exists($p['code'],$temp)){
                            $temp[$p['code']] = $p['class'];
                        }else{
                            if($temp[$p['code']] != $p['class']){
                                $temp[$p['code']] = $p['class'];
                            }
                        }
                    }
                    // convert balik ke format awal
                    $prsNew = [];
                    foreach($temp as $key => $value){
                        $prsNew[] = ['code' => $key,'class'=> $value];
                    }
                }else if(substr($student->last_periode,0,4) < substr(strtolower($d['periode']),0,4) || 
                (substr($student->last_periode,0,4) == substr(strtolower($d['periode']),0,4) && substr($student->last_periode,5,1) < substr(strtolower($d['periode']),5,1))){
                    $prsNew = $d['prs'];
                }else{
                    continue;
                }
                $this->service->update($user->id,[
                    'ips'=>$d['ips'],
                    'ipk' => $d['ipk'],
                    'prs' => json_encode($prsNew),
                    'semester' => $d['semester'],
                    'program' => $d['program'],
                    'last_periode' => strtolower($d['periode'])
                ]);
            }
            
        }
        $res = $this->service->getFormat();
    return $this->success($res,200);
    }
}