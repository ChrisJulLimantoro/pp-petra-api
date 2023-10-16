<?php

namespace App\Models;

use App\Models\Assistant;
use App\Models\ModelUtils;
use App\Models\Practicum;
use App\Repositories\AssistantPracticumRepository;
use App\Services\AssistantPracticumService;
use App\Http\Resources\AssistantPracticumResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AssistantPracticum extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
        'assistant_id',
        'practicum_id',
    ]; 

    /**
     * Rules that applied in this model
     *
     * @var array
     */
    public static function validationRules()
    {
        return [
            'assistant_id' => 'required|uuid|exists:assistants,id',
            'practicum_id' => 'required|uuid|exists:practicums,id',
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
            'assistant_id.required' => 'Assistant is required!',
            'assistant_id.uuid' => 'Assistant must be uuid!',
            'assistant_id.exists' => 'Assistant must be exists!',
            'practicum_id.required' => 'Practicum is required!',
            'practicum_id.uuid' => 'Practicum must be uuid!',
            'practicum_id.exists' => 'Practicum must be exists!',
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
            'assistant_id' => $request['assistant_id'],
            'practicum_id' => $request['practicum_id'],
        ]);
    }


    /**
     * Controller associated with this model
     *
     * @var string
     */

    public function controller()
    {
        return 'App\Http\Controllers\AssistantPracticumController';
    }

    /**
     * Service associated with this model
     *
     * @var object Service
     */
    public function service()
    {
        return new AssistantPracticumService($this);
    }

    /**
     * Repository associated with this model
     *
     * @var object Repository
     */
    public function repository()
    {
        return new AssistantPracticumRepository($this);
    }

    /**
     * Resource associated with this model
     *
     * @var object Resource
     */

    public function resource()
    {
        return new AssistantPracticumResource($this);
    }

    /**
    * Relations associated with this model
    *
    * @var array
    */
    public function relations()
    {
        return ['assistant', 'practicum'];
    }

    public function assistant()
    {
        return $this->belongsTo(Assistant::class,'assistant_id','user_id');
    }

    public function practicum()
    {
        return $this->belongsTo(Practicum::class,'practicum_id','id');
    }
}
