<?php

namespace App\Models;

use App\Models\Assistant;
use App\Models\ModelUtils;
use App\Models\Practicum;
use App\Repositories\RoomRepository;
use App\Services\RoomService;
use App\Http\Resources\RoomResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;


class Room extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=['name','code','capacity']; 

    /**
     * Rules that applied in this model
     *
     * @var array
     */
    public static function validationRules()
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:rooms,code',
            'capacity' => 'required|numeric',
        ];
    }

    /**
     * Messages that applied in this model
     *
     * @var array
     */
    public static function validationMessages()
    {
        return [
            'name.required' => 'room name is required!',
            'code.required' => 'room code is required!',
            'code.unique' => 'room code must be unique!',
            'capacity.required' => 'room capacity is required!',
            'capacity.numeric' => 'room capacity must be a number!',
        ];
    }

    /**
     * Filter data that will be saved in this model
     *
     * @var array
     */
    public function resourceData($request)
    {
        return ModelUtils::filterNullValues([
            'name' => $request['name'],
            'code' => $request['code'],
            'capacity' => $request['capacity'],
        ]);
    }


    /**
     * Controller associated with this model
     *
     * @var string
     */

    public function controller()
    {
        return 'App\Http\Controllers\RoomController';
    }

    /**
     * Service associated with this model
     *
     * @var object Service
     */
    public function service()
    {
        return new RoomService($this);
    }

    /**
     * Repository associated with this model
     *
     * @var object Repository
     */
    public function repository()
    {
        return new RoomRepository($this);
    }

    /**
     * Resource associated with this model
     *
     * @var object Resource
     */

    public function resource()
    {
        return new RoomResource($this);
    }

    /**
    * Relations associated with this model
    *
    * @var array
    */
    public function relations()
    {
        return ['practicums','assistants'];
    }

    public function practicums()
    {
        return $this->hasMany(Practicum::class,'room_id','id');
    }

    public function assistants()
    {
        return $this->hasMany(Assistant::class,'room_id','id');
    }
}
