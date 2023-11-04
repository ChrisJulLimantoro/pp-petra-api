<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Assistant;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Utils\HttpResponseCode;
use Illuminate\Http\Request;

class AssistantController extends BaseController
{
    private $user;
    public function __construct(Assistant $model)
    {
        parent::__construct($model);
        $this->user = new User();
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */

    public function store(Request $request)
    {
        $data = $request->only(['email','name','room_id','description']);

        // cek if user exist
        if($this->user->where('email',$data['email'])->get()->count() == 0){
            $user = User::create(['email' => $data['email'],'name' => $data['name']]);
            UserRole::create(['user_id'=>$user->id,'role_id'=>Role::where('slug','asdos')->first()->id]);
        }else{
            $user = User::where('email',$data['email'])->first();
            // cek if role exist
            if(UserRole::where('user_id',$user->id)->where('role_id',Role::where('slug','asdos')->first()->id)->get()->count() == 0){
                UserRole::create(['user_id'=>$user->id,'role_id'=>Role::where('slug','asdos')->first()->id]);
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
}