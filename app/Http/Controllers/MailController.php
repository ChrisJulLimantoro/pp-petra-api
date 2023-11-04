<?php

namespace App\Http\Controllers;

use App\Mail\applyMail;
use App\Mail\resultMail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    private $student;
    public function __construct(){
        $this->student = new Student();
    }
    public function sendApply($data){
        $mail = new applyMail($data);
        $succ = Mail::to($data['to'])->send($mail);
        if($succ != null){
            return true;
        }else{
            return false;
        }
    }

    public function sendResult($data){
        $mail = new resultMail($data);
        $succ = Mail::to($data['to'])->send($mail);
        if($succ != null){
            return true;
        }else{
            return false;
        }
    }

    public function sendingResult($event_id){
        $result = $this->student->repository()->getResultByStudent($event_id)->toArray();
        $data = [];
        foreach($result as $res){
            $arr = [];
            $arr['to'] = $res['user']['email'];
            $arr['name'] = $res['user']['name'];
            foreach($res['practicums'] as $prac){
                $temp = [
                    'practicum'=>$prac['practicum']['name'],
                    'code'=>$prac['practicum']['code'],
                    'choice' => $prac['choice'],
                ];
                if($prac['practicum']['day'] == 1){
                    $temp['schedule'] = 'Monday,'.$prac['practicum']['time'].'-'.($prac['practicum']['time']+($prac['practicum']['subject']['duration']*100));
                }else if( $prac['practicum']['day'] == 2){
                    $temp['schedule'] = 'Tuesday,'.$prac['practicum']['time'].'-'.($prac['practicum']['time']+($prac['practicum']['subject']['duration']*100));
                }else if( $prac['practicum']['day'] == 3){
                    $temp['schedule'] = 'Wednesday,'.$prac['practicum']['time'].'-'.($prac['practicum']['time']+($prac['practicum']['subject']['duration']*100));
                }else if( $prac['practicum']['day'] == 4){
                    $temp['schedule'] = 'Thursday,'.$prac['practicum']['time'].'-'.($prac['practicum']['time']+($prac['practicum']['subject']['duration']*100));
                }else if( $prac['practicum']['day'] == 5){
                    $temp['schedule'] = 'Friday,'.$prac['practicum']['time'].'-'.($prac['practicum']['time']+($prac['practicum']['subject']['duration']*100));
                }else if( $prac['practicum']['day'] == 6){
                    $temp['schedule'] = 'Saturday,'.$prac['practicum']['time'].'-'.($prac['practicum']['time']+($prac['practicum']['subject']['duration']*100));
                }
                if($prac['accepted'] == 1 or $prac['accepted'] == 3){
                    $temp['status'] = 'accepted';
                }else{
                    $temp['status'] = 'declined';
                }
                $arr['result'][] = $temp;
            }
            $data[] = $arr;
        }
        foreach($data as $d){
            $this->sendResult($d);
        }
        return response()->json([
            'status'=>'success',
            'code'=>200,
            'message'=>'Email sent successfully!',
        ]);
    }
}
