<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Http\Request;

class RoomController extends BaseController
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    /*
        Add new controllers
        OR
        Override existing controller here...
    */

    /**
     * Overwrite Update function
     * 
     */
    public function update(Request $request,$id)
    {
        $fillable = $request->only($this->model->getFillable());

        // check if the code is the same or not
        $cekCode = $this->service->cekCode($fillable['code'],$id);
        if($cekCode){
            unset($fillable['code']);
        }

        ControllerUtils::validateRequest($this->model, $fillable,true);

        $res = $this->service->update($id,$fillable);

        return $this->success($res);
    }
}