<?php

namespace App\Http\Controllers;

use App\Mail\applyMail;
use App\Mail\resultMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendApply(Request $request){
        $data = $request->only(['to','name','apply']);
        $mail = new applyMail($data);
        $succ = Mail::to($data['to'])->send($mail);
        if($succ != null){
            return response()->json([
                'status'=>'success',
                'code'=>200,
                'message'=>'Email sent successfully!'
            ]);
        }else{
            return response()->json([
                'status'=>'error',
                'code'=>400,
                'message'=>'Email failed to send!'
            ]);
        }
    }

    public function sendResult(Request $request){
        $data = $request->only(['to','name','result']);
        $mail = new resultMail($data);
        $succ = Mail::to($data['to'])->send($mail);
        if($succ != null){
            return response()->json([
                'status'=>'success',
                'code'=>200,
                'message'=>'Email sent successfully!'
            ]);
        }else{
            return response()->json([
                'status'=>'error',
                'code'=>400,
                'message'=>'Email failed to send!'
            ]);
        }
    }
}
