<?php

namespace App\Models;

use App\Models\ModelUtils;
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
        'program',
        'semester',
        'prs',
        'ipk',
        'ips'
    ]; 

    /**
     * Rules that applied in this model
     *
     * @var array
     */
    public static function validationRules()
    {
        return [
            'program' => 'required|string',
            'semester' => 'required|integer',
            'prs' => 'required|json',
            'ipk' => 'required|integer',
            'ips' => 'required|integer',
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
            'program.required' => 'Program is required!',
            'program.string' => 'Program must be string!',
            'semester.required' => 'Semester is required!',
            'semester.integer' => 'Semester must be integer!',
            'prs.required' => 'Prs is required!',
            'prs.json' => 'Prs must be json!',
            'ipk.required' => 'Ipk is required!',
            'ipk.integer' => 'Ipk must be integer!',
            'ips.required' => 'Ips is required!',
            'ips.integer' => 'Ips must be integer!',
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
        return ['user'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
