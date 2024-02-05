<?php

namespace App\Http\Controllers;

use App\Mail\applyMail;
use App\Mail\resultMail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;

class MailController extends Controller
{
    private $student;
    private $contact;
    public function __construct(){
        $this->student = new Student();
        $this->contact = new Contact();
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

    public function testing(){
        $mail = new applyMail([
            'to' => 'c14210073@john.petra.ac.id',
            'name' => 'John Doe',
            'apply' => [
                [
                    'practicum' => 'Pemrograman Web',
                    'code' => 'IF184001',
                    'jadwal' => 'Senin, 08:00 - 10:00',
                    'choice' => 1
                ],
            ],
            'contact' => [
                [
                    'type' => 1,
                    'phone' => '081821921212'
                ]
            ],
        ]);
    }

    public function sendingResult($event_id){
        $result = $this->student->repository()->getResultByStudent($event_id)->toArray();
        $data = [];
        $contact = $this->contact->service()->getAll();
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
                if ($prac['practicum']['time'] < 1000) {
                    $time = substr($prac['practicum']['time'], 0, 1) . ":" . substr($prac['practicum']['time'], 1, 2).' - ';
                } else {
                    $time = substr($prac['practicum']['time'], 0, 2) . ":" . substr($prac['practicum']['time'], 2, 3).' - ' ;
                }
                $endTime = $prac['practicum']['time'] + ($prac['practicum']['subject']['duration']*100);
                if($endTime < 1000){
                    $time .= substr($endTime, 0, 1) . ":" . substr($endTime, 1, 2);
                }else{
                    $time .= substr($endTime, 0, 2) . ":" . substr($endTime, 2, 3);
                }
                
                if($prac['practicum']['day'] == 1){
                    $temp['schedule'] = 'Monday,'.$time;
                }else if( $prac['practicum']['day'] == 2){
                    $temp['schedule'] = 'Tuesday,'.$time;
                }else if( $prac['practicum']['day'] == 3){
                    $temp['schedule'] = 'Wednesday,'.$time;
                }else if( $prac['practicum']['day'] == 4){
                    $temp['schedule'] = 'Thursday,'.$time;
                }else if( $prac['practicum']['day'] == 5){
                    $temp['schedule'] = 'Friday,'.$time;
                }else if( $prac['practicum']['day'] == 6){
                    $temp['schedule'] = 'Saturday,'.$time;
                }
                if($prac['accepted'] == 1 or $prac['accepted'] == 3){
                    $temp['status'] = 'accepted';
                }else{
                    $temp['status'] = 'declined';
                }
                $arr['result'][] = $temp;
            }
            $arr['contact'] = $contact;
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
