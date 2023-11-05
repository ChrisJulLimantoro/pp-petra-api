<?php

namespace App\Models;

use App\Models\AssistantPracticum;
use App\Models\ModelUtils;
use App\Models\Room;
use App\Models\User;
use App\Repositories\AssistantRepository;
use App\Services\AssistantService;
use App\Http\Resources\AssistantResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Assistant extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes of primary key
     * 
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
        'user_id',
        'room_id',
        'description',
    ]; 


    /**
     * The attributes that should be hidden for arrays.
     * 
     * @var array
     */
    protected $hidden = ['created_at','updated_at','deleted_at'];

    /**
     * Rules that applied in this model
     *
     * @var array
     */
    public static function validationRules()
    {
        return [
            'user_id' => 'required|uuid|exists:users,id|unique:assistants,user_id',
            'room_id' => 'uuid|exists:rooms,id',
            'description' => 'string',
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
            'user_id.required' => 'User must be filled!',
            'user_id.uuid' => 'User must be uuid!',
            'user_id.exists' => 'User must be exists!',
            'user_id.unique' => 'User already exists as assistant!',
            'room_id.uuid' => 'Room must be uuid!',
            'room_id.exists' => 'Room must be exists!',
            'description.string' => 'Description must be string!',
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
            'user_id' => $request->user_id,
            'room_id' => $request->room_id,
            'description' => $request->description,
        ]);
    }


    /**
     * Controller associated with this model
     *
     * @var string
     */

    public function controller()
    {
        return 'App\Http\Controllers\AssistantController';
    }

    /**
     * Service associated with this model
     *
     * @var object Service
     */
    public function service()
    {
        return new AssistantService($this);
    }

    /**
     * Repository associated with this model
     *
     * @var object Repository
     */
    public function repository()
    {
        return new AssistantRepository($this);
    }

    /**
     * Resource associated with this model
     *
     * @var object Resource
     */

    public function resource()
    {
        return new AssistantResource($this);
    }

    /**
    * Relations associated with this model
    *
    * @var array
    */
    public function relations()
    {
        return ['room','user','assistant_practicum'];
    }

    public function room()
    {
        return $this->belongsTo(Room::class,'room_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function assistant_practicum()
    {
        return $this->hasMany(AssistantPracticum::class,'assistant_id','user_id');
    }
}
