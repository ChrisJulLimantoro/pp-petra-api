<?php

namespace App\Models;

use App\Models\ModelUtils;
use App\Repositories\MasterScheduleRepository;
use App\Services\MasterScheduleService;
use App\Http\Resources\MasterScheduleResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MasterSchedule extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'day',
        'time',
        'duration',
        'name',
        'class'
    ]; 

    /**
     * Rules that applied in this model
     *
     * @var array
     */
    public static function validationRules()
    {
        return [
            'code' => 'required|string',
            'day' => 'required|integer',
            'time' => 'required|integer',
            'duration' => 'required|integer',
            'name' => 'required|string',
            'class' => 'required|string'
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
            'code.required' => 'Kode praktikum harus diisi',
            'code.string' => 'Kode praktikum harus berupa string',
            'day.required' => 'Hari praktikum harus diisi',
            'day.integer' => 'Hari praktikum harus berupa angka',
            'time.required' => 'Waktu praktikum harus diisi',
            'time.integer' => 'Waktu praktikum harus berupa angka',
            'duration.required' => 'Durasi praktikum harus diisi',
            'duration.integer' => 'Durasi praktikum harus berupa angka',
            'name.required' => 'Nama praktikum harus diisi',
            'name.string' => 'Nama praktikum harus berupa string',
            'class.required' => 'Kelas praktikum harus diisi',
            'class.string' => 'Kelas praktikum harus berupa string'
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
            'day' => $request['day'],
            'time' => $request['time'],
            'duration' => $request['duration'],
            'name' => $request['name'],
            'class' => $request['class']
        ]);
    }


    /**
     * Controller associated with this model
     *
     * @var string
     */

    public function controller()
    {
        return 'App\Http\Controllers\MasterScheduleController';
    }

    /**
     * Service associated with this model
     *
     * @var object Service
     */
    public function service()
    {
        return new MasterScheduleService($this);
    }

    /**
     * Repository associated with this model
     *
     * @var object Repository
     */
    public function repository()
    {
        return new MasterScheduleRepository($this);
    }

    /**
     * Resource associated with this model
     *
     * @var object Resource
     */

    public function resource()
    {
        return new MasterScheduleResource($this);
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
