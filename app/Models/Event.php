<?php

namespace App\Models;

use App\Models\ModelUtils;
use App\Repositories\EventRepository;
use App\Services\EventService;
use App\Http\Resources\EventResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Event extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
        'name',
        'status',
        'start_date',
        'end_date',
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
            'name'=> 'required|string',
            'status'=> 'integer',
            'start_date'=> 'required|date',
            'end_date'=> 'required|date',
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
            'name.required'=> 'Name is required!',
            'name.string'=> 'Name must be string!',
            'status.integer'=> 'Status must be integer!',
            'start_date.required'=> 'Start date is required!',
            'start_date.date'=> 'Start date must be date!',
            'end_date.required'=> 'End date is required!',
            'end_date.date'=> 'End date must be date!',
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
            'name'=> $request->name,
            'status'=> $request->status,
            'start_date'=> $request->start_date,
            'end_date'=> $request->end_date
        ]);
    }


    /**
     * Controller associated with this model
     *
     * @var string
     */

    public function controller()
    {
        return 'App\Http\Controllers\EventController';
    }

    /**
     * Service associated with this model
     *
     * @var object Service
     */
    public function service()
    {
        return new EventService($this);
    }

    /**
     * Repository associated with this model
     *
     * @var object Repository
     */
    public function repository()
    {
        return new EventRepository($this);
    }

    /**
     * Resource associated with this model
     *
     * @var object Resource
     */

    public function resource()
    {
        return new EventResource($this);
    }

    /**
    * Relations associated with this model
    *
    * @var array
    */
    public function relations()
    {
        return ['student_practicums'];
    }

    public function student_practicums()
    {
        return $this->hasMany(StudentPracticum::class,'event_id','id');
    }

}
