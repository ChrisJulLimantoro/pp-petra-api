<?php

namespace App\Models;

use App\Models\ModelUtils;
use App\Models\Practicum;
use App\Repositories\SubjectRepository;
use App\Services\SubjectService;
use App\Http\Resources\SubjectResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Subject extends Model
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
        'code',
        'sks',
        'semester',
        'duration',
        'program'
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
            'name' => 'required|string',
            'code' => 'required|string|max:8|unique:subjects,code',
            'sks' => 'required|integer',
            'semester' => 'required|integer',
            'duration' => 'required|integer',
            'program' => 'required|string',
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
            'name.required' => 'Name is required!',
            'name.string' => 'Name must be string!',
            'code.required' => 'Code is required!',
            'code.string' => 'Code must be string!',
            'code.max' => 'Code must be max 8 characters!',
            'code.unique' => 'Code must be unique!',
            'sks.required' => 'Sks is required!',
            'sks.integer' => 'Sks must be integer!',
            'semester.required' => 'Semester is required!',
            'semester.integer' => 'Semester must be integer!',
            'duration.required' => 'Duration is required!',
            'duration.integer' => 'Duration must be integer!',
            'program.required' => 'Program is required!',
            'program.string' => 'Program must be string!',
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
            'sks' => $request['sks'],
            'semester' => $request['semester'],
            'duration' => $request['duration'],
            'program' => $request['program'],
        ]);
    }


    /**
     * Controller associated with this model
     *
     * @var string
     */

    public function controller()
    {
        return 'App\Http\Controllers\SubjectController';
    }

    /**
     * Service associated with this model
     *
     * @var object Service
     */
    public function service()
    {
        return new SubjectService($this);
    }

    /**
     * Repository associated with this model
     *
     * @var object Repository
     */
    public function repository()
    {
        return new SubjectRepository($this);
    }

    /**
     * Resource associated with this model
     *
     * @var object Resource
     */

    public function resource()
    {
        return new SubjectResource($this);
    }

    /**
    * Relations associated with this model
    *
    * @var array
    */
    public function relations()
    {
        return ['practicums'];
    }

    public function practicums()
    {
        return $this->hasMany(Practicum::class,'subject_id','id');
    }

}
