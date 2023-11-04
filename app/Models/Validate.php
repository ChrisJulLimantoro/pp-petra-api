<?php

namespace App\Models;

use App\Models\ModelUtils;
use App\Repositories\ValidateRepository;
use App\Services\ValidateService;
use App\Http\Resources\ValidateResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Validate extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
        'student_id',
        'event_id',
        'validate',
    ]; 

    /**
     * Rules that applied in this model
     *
     * @var array
     */
    public static function validationRules()
    {
        return [
            'student_id' => 'required|exists:students,user_id',
            'event_id' => 'required|exists:events,id',
            'validate'=> 'integer|between:0,1',
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
            'student_id.required' => 'Student ID is required',
            'student_id.exists' => 'Student ID is not exists',
            'event_id.required' => 'Event ID is required',
            'event_id.exists' => 'Event ID is not exists',
            'validate.integer' => 'Validate must be integer',
            'validate.between' => 'Validate must be 0 or 1',
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
            'student_id' => $request->student_id,
            'event_id' => $request->event_id,
            'validate' => $request->validate ?? '1',
        ]);
    }


    /**
     * Controller associated with this model
     *
     * @var string
     */

    public function controller()
    {
        return 'App\Http\Controllers\ValidateController';
    }

    /**
     * Service associated with this model
     *
     * @var object Service
     */
    public function service()
    {
        return new ValidateService($this);
    }

    /**
     * Repository associated with this model
     *
     * @var object Repository
     */
    public function repository()
    {
        return new ValidateRepository($this);
    }

    /**
     * Resource associated with this model
     *
     * @var object Resource
     */

    public function resource()
    {
        return new ValidateResource($this);
    }

    /**
    * Relations associated with this model
    *
    * @var array
    */
    public function relations()
    {
        return [];
    }

}
