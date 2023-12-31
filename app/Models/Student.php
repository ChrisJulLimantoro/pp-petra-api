<?php

namespace App\Models;

use App\Models\ModelUtils;
use App\Models\StudentPracticum;
use App\Models\User;
use App\Models\Validate;
use App\Repositories\StudentRepository;
use App\Services\StudentService;
use App\Http\Resources\StudentResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Student extends Model
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
        'program',
        'semester',
        'prs',
        'ipk',
        'ips',
        'last_periode'
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
            'user_id' => 'required|uuid|exists:users,id|unique:students,user_id', // 'unique:students,user_id' means that the user_id must be unique in students table
            'program' => 'required|string',
            'semester' => 'required|integer',
            'prs' => 'json',
            'ipk' => 'numeric',
            'ips' => 'numeric',
            'last_periode' => 'string'
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
            'user_id.unique' => 'User already exists as student!',
            'program.required' => 'Program is required!',
            'program.string' => 'Program must be string!',
            'semester.required' => 'Semester is required!',
            'semester.integer' => 'Semester must be integer!',
            'prs.json' => 'Prs must be json!',
            'ipk.numeric' => 'Ipk must be numeric!',
            'ips.numeric' => 'Ips must be numeric!',
            'last_periode' => 'Last Periode must be string!'
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
            'user_id' => $request['user_id'],
            'program' => $request['program'],
            'semester' => $request['semester'],
            'prs' => $request['prs'],
            'ipk' => $request['ipk'],
            'ips' => $request['ips'],
        ]);
    }


    /**
     * Controller associated with this model
     *
     * @var string
     */

    public function controller()
    {
        return 'App\Http\Controllers\StudentController';
    }

    /**
     * Service associated with this model
     *
     * @var object Service
     */
    public function service()
    {
        return new StudentService($this);
    }

    /**
     * Repository associated with this model
     *
     * @var object Repository
     */
    public function repository()
    {
        return new StudentRepository($this);
    }

    /**
     * Resource associated with this model
     *
     * @var object Resource
     */

    public function resource()
    {
        return new StudentResource($this);
    }

    /**
    * Relations associated with this model
    *
    * @var array
    */
    public function relations()
    {
        return ['user','practicums','validate'];
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function practicums()
    {
        return $this->hasMany(StudentPracticum::class,'student_id','user_id');
    }

    public function validate()
    {
        return $this->hasMany(Validate::class,'student_id','user_id');
    }
}
