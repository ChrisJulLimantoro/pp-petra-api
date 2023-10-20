<?php

namespace App\Models;

use App\Models\Event;
use App\Models\ModelUtils;
use App\Models\Practicum;
use App\Models\Student;
use App\Repositories\StudentPracticumRepository;
use App\Services\StudentPracticumService;
use App\Http\Resources\StudentPracticumResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StudentPracticum extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'practicum_id',
        'attempt',
        'choice',
        'accepted',
        'rejected_reason',
    ]; 

    /**
     * Rules that applied in this model
     *
     * @var array
     */
    public static function validationRules()
    {
        return [
            'student_id' => 'required|uuid|exists:students,user_id',
            'practicum_id' => 'required|uuid|exists:practicums,id',
            'event_id' => 'required|uuid|exists:events,id',
            'choice' => 'required|integer',
            'accepted' => 'integer|in:0,1,2',
            'rejected_reason' => 'string',
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
            'student_id.required' => 'Student is required!',
            'student_id.uuid' => 'Student must be uuid!',
            'student_id.exists' => 'Student must be exists!',
            'practicum_id.required' => 'Practicum is required!',
            'practicum_id.uuid' => 'Practicum must be uuid!',
            'practicum_id.exists' => 'Practicum must be exists!',
            'attempt.required' => 'Attempt is required!',
            'attempt.integer' => 'Attempt must be integer!',
            'choice.required' => 'Choice is required!',
            'choice.integer' => 'Choice must be integer!',
            'accepted.required' => 'Accepted is required!',
            'accepted.integer' => 'Accepted must be integer!',
            'accepted.in' => 'Accepted must be 0, 1, or 2!',
            'rejected_reason.string' => 'Rejected reason must be string!',
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
            'student_id' => $request['student_id'],
            'practicum_id' => $request['practicum_id'],
            'event' => $request['event'],
            'choice' => $request['choice'],
            'accepted' => $request['accepted'],
            'rejected_reason' => $request['rejected_reason'],
        ]);
    }


    /**
     * Controller associated with this model
     *
     * @var string
     */

    public function controller()
    {
        return 'App\Http\Controllers\StudentPracticumController';
    }

    /**
     * Service associated with this model
     *
     * @var object Service
     */
    public function service()
    {
        return new StudentPracticumService($this);
    }

    /**
     * Repository associated with this model
     *
     * @var object Repository
     */
    public function repository()
    {
        return new StudentPracticumRepository($this);
    }

    /**
     * Resource associated with this model
     *
     * @var object Resource
     */

    public function resource()
    {
        return new StudentPracticumResource($this);
    }

    /**
    * Relations associated with this model
    *
    * @var array
    */
    public function relations()
    {
        return ['student','practicum','event'];
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id','user_id');
    }

    public function practicum()
    {
        return $this->belongsTo(Practicum::class,'practicum_id','id');
    }
    public function event()
    {
        return $this->belongsTo(Event::class,'event_id','id');
    }

}
