<?php

namespace App\Models;

use App\Models\AssistantPracticum;
use App\Models\ModelUtils;
use App\Models\Room;
use App\Models\StudentPracticum;
use App\Models\Subject;
use App\Repositories\PracticumRepository;
use App\Services\PracticumService;
use App\Http\Resources\PracticumResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Practicum extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
        'code',
        'name',
        'quota',
        'subject_id',
        'room_id',
    ]; 

    /**
     * Rules that applied in this model
     *
     * @var array
     */
    public static function validationRules()
    {
        return [
            'code' => 'required|string|max:8',
            'name' => 'required|string',
            'quota' => 'required|integer',
            'subject_id' => 'required|uuid|exists:subjects,id',
            'room_id' => 'required|uuid|exists:rooms,id',
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
            'code.required' => 'Code is required!',
            'code.string' => 'Code must be string!',
            'code.max' => 'Code must be max 8 characters!',
            'name.required' => 'Name is required!',
            'name.string' => 'Name must be string!',
            'quota.required' => 'Quota is required!',
            'quota.integer' => 'Quota must be integer!',
            'subject_id.required' => 'Subject is required!',
            'subject_id.uuid' => 'Subject must be uuid!',
            'subject_id.exists' => 'Subject not found!',
            'room_id.required' => 'Room is required!',
            'room_id.uuid' => 'Room must be uuid!',
            'room_id.exists' => 'Room not found!',
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
            'code' => $request['code'],
            'name' => $request['name'],
            'quota' => $request['quota'],
            'subject_id' => $request['subject_id'],
            'room_id' => $request['room_id'],
        ]);
    }


    /**
     * Controller associated with this model
     *
     * @var string
     */

    public function controller()
    {
        return 'App\Http\Controllers\PracticumController';
    }

    /**
     * Service associated with this model
     *
     * @var object Service
     */
    public function service()
    {
        return new PracticumService($this);
    }

    /**
     * Repository associated with this model
     *
     * @var object Repository
     */
    public function repository()
    {
        return new PracticumRepository($this);
    }

    /**
     * Resource associated with this model
     *
     * @var object Resource
     */

    public function resource()
    {
        return new PracticumResource($this);
    }

    /**
    * Relations associated with this model
    *
    * @var array
    */
    public function relations()
    {
        return ['subject', 'room','assistantPracticum','studentPracticum'];
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_id','id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class,'room_id','id');
    }

    public function assistantPracticum()
    {
        return $this->hasMany(AssistantPracticum::class,'practicum_id','id');
    }

    public function studentPracticum()
    {
        return $this->hasMany(StudentPracticum::class,'practicum_id','id');
    }
}
